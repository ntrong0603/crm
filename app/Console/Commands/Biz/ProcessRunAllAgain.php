<?php
/**
 * Chaỵ lại tất cả các batch và cập nhật dữ liệu  từ đầu
 *
 * @package App\Console\Commands\Biz
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Naruto<naruto_vn@monotos.biz>
 */
namespace App\Console\Commands\Biz;

use Illuminate\Console\Command;
use App\Models\Biz\OcCustomer;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;
use Illuminate\Support\Facades\Artisan;
class ProcessRunAllAgain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biz_process:run-all-again';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command run all batch again';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {       
        //Get class name
        $class = (new \ReflectionClass($this))->getShortName();
        //Config folder save log
        config(['logging.channels.batch.path' => storage_path("logs/batches/$class/history.log")]);
        //Start event process batch
        event(new eventBatch(['status'=>'start']));
        $this->processAll();
        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }

    public function processAll()
    {
        $begin = new \DateTime('2018-07-01');
        $end = new \DateTime();
        
        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($begin, $interval, $end);

        (new OcCustomer)->resetCustomerTracking();
        foreach ($period as $key => $dt) {
            $date = $dt->format("Y-m-t");
            if($date <= date('Y-m-d')) {
                echo "=======".$date."=======\n";
                (new OcCustomer)->calculateAgain($date);
                Artisan::call('biz_process:customer-rank-calculate --date='.$date);
                Artisan::call('biz_process:customer-rank-analytic-calculate --date='.$date);
                Artisan::call('biz_process:transition-rate-calculate --date='.$date);
                Artisan::call('biz_process:customer-ltv --date='.$date);
            }
        }
        Artisan::call('biz_process:customer-rank-calculate');
        Artisan::call('biz_process:customer-rank-analytic-calculate');
        Artisan::call('biz_process:transition-rate-calculate');
        Artisan::call('biz_process:customer-ltv');
        Artisan::call('biz_process:revenue-calculate all');
    }
}
