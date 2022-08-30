<?php

/**
 * Mapping dữ liệu dt_mail_sent từ email setting và email schedule
 * Logic: 1 tiếng chạy 1 lần, chạy hàng ngày
 *
 * @package App\Console\Commands\Df
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Naruto<naruto_vn@monotos.biz>
 */

namespace App\Console\Commands\Df;

use App\Models\Df\OcCustomer;
use App\Models\Df\OcProduct;
use App\Models\Df\OcCart;
use App\Events\Batch as eventBatch;
use App\Models\Df\DtMailSchedule;
use App\Models\Df\DtMailSent;
use App\Console\ProcessDfCommand;

class ProcessDataMailCart extends ProcessDfCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df_process:data-mail-cart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command mapping data mail sent last shipped';

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
        event(new eventBatch(['status' => 'start']));
        $this->processMappingMail();
        //End event process batch
        event(new eventBatch(['status' => 'end']));
    }

    public function processMappingMail()
    {
        //Reset status
        (new DtMailSchedule)->resetSchedule(self::CART);
        //End reset status

        $dataSchedule = (new DtMailSchedule)->getSchedule($mailType = 1, $standard_id = self::CART);
        $contentBody = '';
        if ($dataSchedule->count()) {
            $products = (new OcCart)->getDataCart();
            $arrId = array_keys($products);
            foreach ($dataSchedule as $key => $schedule) {
                $dataCustomer = (new OcCustomer)->getCustomerCart($arrId, $schedule);
                if ($dataCustomer) {
                    foreach ($dataCustomer as $key => $customer) {

                        $dataProduct = $products[$customer['customer_id']] ?? [];
                        $contentBody = $this->renderHtml($dataProduct);
                        //Mail content
                        if ($schedule['mail_template_option'] == 0) {
                            $content = $schedule['mail_content_html'];
                        } else {
                            $content = $schedule['mail_content_text'];
                        }
                        $token = md5(uniqid('', true));

                        //Process time send mail
                        $timeSend = $this->caculateTimeSentCart();
                        //End time send mail

                        $dataReplace = $customer;
                        $dataReplace['token']    = $token;
                        $dataReplace['dataBody'] = $contentBody;
                        $dataReplace['dataDate'] = date('Y年m月d日', strtotime($timeSend));
                        $mailcontent = $this->replaceParam($dataReplace, $content, $schedule['mail_template_option']);
                        $mailcontent = str_replace('href="', 'href="' . route('url.process') . "/?mall=df&token=" . $token . "&url=", $mailcontent);

                        //Subject mail
                        $subject = str_replace('[customer_name]', $customer['firstname'], $schedule['subject']);

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
                        $this->insertMailSent($dataSentMail, $customer, $action = "cart");

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

    /**
     * Render hrml product list
     *
     * @param   [type]  $dataProduct  [$dataProduct description]
     *
     * @return  [type]                [return description]
     */
    public function renderHtml($dataProduct)
    {
        $productRecommend = [];
        $dataProduct = array_slice($dataProduct,0, 3);
        foreach ($dataProduct as $key => $pr) {
            $pTmp = (new OcProduct)->getProductTogether($pr['model']);
            if($pTmp) {
                $productRecommend[] = $pTmp[0];
            }
        }
        $data = [
            'dataProduct' => $dataProduct,
            'productRecommend' => $productRecommend,
            'keyTemplate'      => 'cart',
            'param'            => '?utm_source=df_h&utm_medium=email&utm_campaign=cart-mail&utm_content=html',
            'mall'             => 'df',
        ];
        $html = view('admin.mail-template.sub-item.slot-data-body-cart', $data)->render();
        return $html;
    }
}
