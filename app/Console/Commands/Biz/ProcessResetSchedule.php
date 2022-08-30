<?php
/**
 * Mapping dữ liệu dt_mail_sent từ email setting và email schedule
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
use Illuminate\Support\Carbon;
use App\Models\Biz\DtMailSchedule;
use App\Models\Biz\DtMailSent;
use App\Console\ProcessBizCommand;

class ProcessResetSchedule extends ProcessBizCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biz_process:reset-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command reset schedule';

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
        $this->processResetSchedule();
        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }


    public function processResetSchedule() {

        return  (new DtMailSchedule)->resetSchedule();
        
    }
}
