<?php
/**
 * Process Clear Log Query
 *
 * @package    App\Console\Commands
 * @subpackage ProcessClearLogQuery
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Pham Son<songoku_vn@monotos.biz>
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Biz\LogQueryProcessBatches;
use App\Models\Biz\LogQueryProcessScreens;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;
use DB;

class ProcessClearLogQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:clear-log-query {limit=noLimit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Count success
     *
     * @var int
     */

    protected $countSuccess;
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
     */
    public function handle()
    {
        //Get class name
        $class = (new \ReflectionClass($this))->getShortName();
        //Config folder save log
        config(['logging.channels.batch.path' => storage_path("logs/batches/$class/history.log")]);
        //Start event process batch
        event(new eventBatch(['status'=>'start']));
        $checkLimit = $this->argument('limit');
        $limit = '';
        if ($checkLimit != 'noLimit' && is_numeric($checkLimit)) {
            $limit = $checkLimit;
        }
        $date = date('Y-m-d', strtotime('-7 days'));
        
        /*clear data table log_query_process_batchess */
        $this->countSuccess = 0;

        Log::info("=== Process Clear Data Log Query Batches ===");
        try {
            $result = LogQueryProcessBatches::where('created_date', '<=', $date);
            if ($limit != '') {
                $result->limit($limit);
            }
            $this->countSuccess = $result->delete();

        } catch (\Throwable $e) {
            report($e);
        }
        Log::info("======= Process Delete Log Query Batches Sucess : " . $this->countSuccess . " =======");
        /*End clear data table log_query_process_batches*/

        /*clear data table log_query_process_batchess */
        $this->countSuccess = 0;

        Log::info("=== Process Clear Data Log Query Screen ===");
        try {
            $result = LogQueryProcessScreens::where('created_date', '<=', $date);
            if ($limit != '') {
                $result->limit($limit);
            }
            
            $this->countSuccess =  $result->delete();
        } catch (\Throwable $e) {
            report($e);
        }
        Log::info("======= Process Delete Log Query Screen Sucess : " . $this->countSuccess . " =======");
        /*End clear data table log_query_process_batches*/

        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }
}
