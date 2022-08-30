<?php
/**
 * Mapping dữ liệu dt_mail_sent từ email setting và email schedule
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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;
use App\Models\Df\DtMailSchedule;
use App\Models\Df\DtMailSent;
use App\Console\ProcessDfCommand;

class ProcessDataMailType2 extends ProcessDfCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df_process:data-mail-type-2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command mapping data mail sent';

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
        $this->processMappingMail();
        //End event process batch
        event(new eventBatch(['status'=>'end']));
    }

    public function processMappingMail() {

        $contentBody = '';
        $dataSchedule = (new DtMailSchedule)->getSchedule($mailType = 2, $standard_id = null);
        if($dataSchedule->count()) {
            foreach ($dataSchedule as $key => $schedule) {
                $dataCustomer = (new OcCustomer)->getCustomerType2($schedule);
                $dataCustomer = $dataCustomer->toArray();

                if($dataCustomer) {
                    foreach ($dataCustomer as $key => $customer) {

                        //Mail content
                        if ($schedule['mail_template_option'] == 0) {
                            $content = $schedule['mail_content_html'];
                        } else {
                            $content = $schedule['mail_content_text'];
                        }
                        $token = md5(uniqid('', true));

                        $dataReplace             = $customer;
                        $dataReplace['token']    = $token;
                        $dataReplace['dataBody'] = $contentBody;
                        $mailcontent             = $this->replaceParam($dataReplace, $content, $schedule['mail_template_option']);
                        $mailcontent = str_replace('href="', 'href="'.route('url.df_process')."/?token=".$token."&url=", $mailcontent);


                        //Subject mail
                        $subject = str_replace('[customer_name]', $customer['firstname'], $schedule['subject']);

                        //Process time send mail
                        $timeSend = $this->caculateTimeSent($schedule);
                        //End time send mail

                        //Insert data send mail
                        $dataSentMail['mail_setting_id'] = $schedule['mail_setting_id'];
                        $dataSentMail['schedule_id']     = $schedule['schedule_id'];
                        $dataSentMail['mail_from']       = $schedule['mail_from'];
                        $dataSentMail['mail_from_name']  = $schedule['mail_from_name'];
                        $dataSentMail['token']           = $token;
                        $dataSentMail['timeSend']        = $timeSend;
                        $dataSentMail['subject']         = $subject;
                        $dataSentMail['mailcontent']     = $mailcontent;
                        $dataSentMail['in_ope_cd']       = 1; //id account admin
                        $this->insertMailSent($dataSentMail, $customer);

                        //End insert data
                    }
                }
                $schedule->is_run = 1;
                $schedule->save();
            }
        } else {
            echo 'No data';
        }
    }
}
