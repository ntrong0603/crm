<?php
/**
 * Command customer rank calculate
 * This batch run at 21:30 every end of month
 *
 * @package App\Console\Commands\Df
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Naruto<naruto_vn@monotos.biz>
 */
namespace App\Console\Commands\Df;

use Illuminate\Console\Command;
use App\Models\Df\OcCustomer;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;

class ProcessCustomerRankCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df_process:customer-rank-calculate {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command customer rank calculate';

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

        $this->processCalculateRank($date);

        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }

    public function processCalculateRank($date = null)
    {
        return (new OcCustomer)->calculateRank($date);
    }
}
