<?php

/**
 * Process calculate Revenue
 *
 * @package    App\Console\Commands\Biz
 * @subpackage ProcessRevenueCalculate
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Truong Nghia<truong.van.nghia@rivercrane.vn>
 */

namespace App\Console\Commands\Biz;

use Illuminate\Console\Command;
use App\Models\Biz\MstCustomerStatistics;
use App\Models\Biz\OcCustomer;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;

class ProcessRevenueCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biz_process:revenue-calculate {check=noCheck}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $check = $this->argument('check');
        $modelOC    = new OcCustomer();
        $modelCS    = new MstCustomerStatistics();
        if ($check === 'noCheck') {
            $dataNew    = $modelOC->getDataNewRevenue();
            $dataRepeat = $modelOC->getDataRepeatRevenue();
            $modelCS->where('monthly', date('Ym'))->update([
                'new_sold_price'    => $dataNew->total_new_revenue,
                'repeat_sold_price' => $dataRepeat->total_repeat_revenue,
            ]);
        } else {
            $dataNew    = $modelOC->getDataNewRevenueAll();
            foreach ($dataNew as $dataN) {
                $modelCS->where('monthly', date('Ym', strtotime($dataN->delivery_date)))->update([
                    'new_sold_price'    => $dataN->total_new_revenue,
                    'up_ope_cd'         => 1
                ]);
            }
            $dataRepeat = $modelOC->getDataRepeatRevenueAll();
            foreach ($dataRepeat as $dataR) {
                $modelCS->where('monthly', date('Ym', strtotime($dataR->delivery_date)))->update([
                    'repeat_sold_price' => $dataR->total_repeat_revenue,
                    'up_ope_cd'         => 1
                ]);
            }
        }

        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }
}
