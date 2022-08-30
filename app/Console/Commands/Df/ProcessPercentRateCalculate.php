<?php
/**
 * Command Transition Rate Calculate
 * table mst_customer_statistics
 *
 * @package App\Console\Commands\Df
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Naruto<naruto_vn@monotos.biz>
 */
namespace App\Console\Commands\Df;

use Illuminate\Console\Command;
use App\Models\Df\OcCustomer;
use App\Models\Df\MstCustomerStatistics;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;

class ProcessPercentRateCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df_process:percent-rate-calculate {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command percent Rate Calculate table mst_customer_statistics';

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
        $date             = $this->option('date') ?? '';
        //Get class name
        $class = (new \ReflectionClass($this))->getShortName();
        //Config folder save log
        config(['logging.channels.batch.path' => storage_path("logs/batches/$class/history.log")]);
        //Start event process batch
        event(new eventBatch(['status'=>'start']));

        $this->processCalculatePercentRate($date);

        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }

    public function processCalculatePercentRate($date)
    {
        return (new MstCustomerStatistics)->caculatePercent($date);
    }
}
