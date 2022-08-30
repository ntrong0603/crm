<?php
/**
 * Process auto send mail
 *
 * @package    App\Console\Commands\Df
 * @subpackage ProcessAutoSendMail
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Truong Nghia<shikamaru_vn@monotos.biz>
 */

namespace App\Console\Commands\Df;

use Illuminate\Console\Command;
use App\Models\Df\DtMailSent;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;
use App\Mail\CustomMailable;
use Illuminate\Support\Facades\Mail;
use Validator;

class ProcessAutoSendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df_process:auto-send-mail {step=noStep}';

    /**
     * Define limit record
     *
     * @var int
     */
    protected $limit = 100;

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
        $step = $this->argument('step');
        if ($step === 'noStep') {
            $step = 0;
        }
        $modelSM = new DtMailSent();
        $datas = $modelSM->getDataSendMailAuto($step, $this->limit);
        if ($datas->count()) {
            $arrIndex = $datas->pluck('index');
            $modelSM->whereIn('index', $arrIndex)->update(['send_status' => 2]);
            foreach ($datas as $data) {
                try {
                    if (config('app.env') !== 'production') {
                        $sendMail = Mail::to(explode(',', crm_config('email_test')), 'DIY FACTORY');
                    } else {
                        $validator = Validator::make(['mail_to' => $data->mail_to], [
                            'mail_to'=>'email:rfc'
                        ]);
                        if ($validator->fails()) {
                            $modelSM->where('index', $data->index)->update([
                                'is_send_error'        => 1,
                                'log_validation_error' => $validator->messages()->toJson(JSON_UNESCAPED_UNICODE)
                            ]);
                            continue;
                        }

                        $sendMail = Mail::to($data->mail_to, $data->customer_name);
                    }
                    $arrOther = [];
                    if (!empty($data->mail_cc)) {
                        $arrOther['cc'] = $data->mail_cc;
                    }
                    if (!empty($data->mail_bc)) {
                        $arrOther['bcc'] = $data->mail_bc;
                    }
                    if (!empty($data->mail_from)) {
                        $arrOther['from'] = ['email' => $data->mail_from, 'name' => $data->mail_from_name];
                    }
                    Log::info("Send mail id: {$data->index}");
                    $sendMail->send(new CustomMailable(false, $data->mail_content, $data->mail_subject, '', $arrOther));
                    $modelSM->where('index', $data->index)
                            ->update([
                                'send_status'   => 1,
                                'is_send_error' => 0,
                                'up_ope_cd'     => 1,
                            ]);
                } catch (\Throwable $e) {
                   $modelSM->where('index', $data->index)->update([
                       'is_send_error'  => 1,
                       'log_send_mail' => json_encode($e->getMessage())
                       ]);
                }
            }
        }
        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }
}
