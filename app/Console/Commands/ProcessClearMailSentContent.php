<?php
/**
 * Process Clear Mail Sent Content
 *
 * @package    App\Console\Commands
 * @subpackage ProcessClearMailSentContent
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Pham Son<songoku_vn@monotos.biz>
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Biz\DtMailSentContent as DtMailSentContentBiz;
use App\Models\Df\DtMailSentContent as DtMailSentContentDf;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;
use DB;

class ProcessClearMailSentContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:clear-mail-sent-content {limit=noLimit}';

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

        Log::info("=== Process Clear Data Mail Content Biz ===");
        try {
            $result = DtMailSentContentBiz::where('date_add', '<=', $date);
            if ($limit != '') {
                $result->limit($limit);
            }
            $this->countSuccess = $result->delete();
        } catch (\Throwable $e) {
            report($e);
        }
        Log::info("======= Process Delete Data Mail Content Biz Sucess : " . $this->countSuccess . " =======");
        /*End clear data table log_query_process_batches*/

        /*clear data table log_query_process_batchess */
        $this->countSuccess = 0;

        Log::info("=== Process Clear Data Mail Content Df ===");
        try {
            $result = DtMailSentContentDf::where('date_add', '<=', $date);
            if ($limit != '') {
                $result->limit($limit);
            }
            $this->countSuccess = $result->delete();
        } catch (\Throwable $e) {
            report($e);
        }
        Log::info("======= Process Delete Data Mail Content Df Sucess : " . $this->countSuccess . " =======");
        /*End clear data table log_query_process_batches*/

        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }
}
