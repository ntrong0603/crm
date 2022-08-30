<?php
/**
 * Process auto send mail
 *
 * @package    App\Console\Commands\Biz
 * @subpackage ProcessClearLastview
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Naruto<naruto_vn@monotos.biz>
 */

namespace App\Console\Commands\Biz;

use Illuminate\Console\Command;
use App\Models\Biz\DtMailSent;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;
use App\Models\Biz\OcProductRecentView;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Validator;

class ProcessClearLastview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biz_process:clear-last-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear data oc_product_recent_view ';

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
     * @return int
     */
    public function handle()
    {
        //Get class name
        $class = (new \ReflectionClass($this))->getShortName();
        //Config folder save log
        config(['logging.channels.batch.path' => storage_path("logs/batches/$class/history.log")]);
        //Start event process batch
        event(new eventBatch(['status'=>'start']));

        $this->clearDataLastview();

        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }

    public function clearDataLastview() {
        OcProductRecentView::whereDate('date', '<', Carbon::now()->sub(1, 'day')->format('Y-m-d'))->delete();
    }
}
