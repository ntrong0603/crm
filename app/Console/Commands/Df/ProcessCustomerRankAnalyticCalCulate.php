<?php
/**
 * Command ccustomer rank analysis calculate
 * This batch run at 22:00 every end of month
 *
 * @package App\Console\Commands\Df
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Naruto<naruto_vn@monotos.biz>
 */
namespace App\Console\Commands\Df;

use Illuminate\Console\Command;
use App\Models\Df\MstCustomerStatistics;
use App\Events\Batch as eventBatch;
use Illuminate\Support\Facades\Artisan;
class ProcessCustomerRankAnalyticCalCulate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df_process:customer-rank-analytic-calculate {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command customer rank analysis calculate';

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
        $this->processCalculateStatic($date);
        Artisan::call('df_process:percent-rate-calculate --date='.$date);
        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }

    public function processCalculateStatic($date = null)
    {
        return (new MstCustomerStatistics)->calculateStatic($date);
    }
}
