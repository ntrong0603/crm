<?php

namespace App\Listeners;

use App\Events\Batch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Biz\MstBatchStatus;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Notifications\Notifiable;

class ProcessLogBatch
{
    use Notifiable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Batch  $event
     * @return void
     */
    public function handle(Batch $event)
    {
        $modelBS = new MstBatchStatus();
        $argv = $_SERVER['argv'];
        if (isset($argv[1])) {
            $signature = $argv[1];
        } else {
            $signature = '';
            crm_report("*Error signature*");
        }
        // Batch start process
        if ($event->process['status'] === 'start') {
            Log::info("====Start Batch {$signature}====");
            $data = $modelBS->getDataBySignature($signature);
            if (!empty($data)) {
                if ($data->is_active === 0) {
                    Log::info("Batch {$signature} dont active");
                    Log::info("====End Batch {$signature}====");
                    exit;
                } else {
                    $data->error_message = '';
                    $data->status_flag = 1;
                    $data->save();
                }
            }
        }
        //Batch end process
        if ($event->process['status'] === 'end') {
            $data = $modelBS->getDataBySignature($signature);
            if (!empty($data)) {
                if ($data->status_flag !== 2) {
                    $data->status_flag = 0;
                    $data->save();
                }
            }
            Log::info("====End Batch {$signature}====");
        }
        // Batch have error
        if ($event->process['status'] === 'stop') {
            $data = $modelBS->getDataBySignature($signature);
            if (!empty($data)) {
                $data->error_message = $event->process['error'];
                $data->status_flag = 2;
                $data->save();
            }
            Log::info("====Stop batch {$signature}====");
        }
    }
}
