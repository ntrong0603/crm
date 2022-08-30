<?php

/**
 * Mapping dữ liệu dt_mail_sent từ email setting và email schedule
 *
 * @package App\Console\Commands\Biz
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Sesshomaru<sesshomaru_vn@monotos.biz>
 */

namespace App\Console\Commands\Biz;

use App\Models\Biz\OcCustomer;
use App\Events\Batch as eventBatch;
use App\Models\Biz\DtMailSchedule;
use App\Console\ProcessBizCommand;
use App\Models\Biz\MstCategoryLarge;
use App\Models\Biz\OcProduct;

class ProcessDataMailRanking extends ProcessBizCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biz_process:data-mail-ranking';

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
        $this->info('---Start Batch---');
        $t1 = microtime(true);
        //Get class name
        $class = (new \ReflectionClass($this))->getShortName();
        //Config folder save log
        config(['logging.channels.batch.path' => storage_path("logs/batches/$class/history.log")]);
        //Start event process batch
        event(new eventBatch(['status' => 'start']));
        $this->processMappingMail();
        //End event process batch
        event(new eventBatch(['status' => 'end']));
        $t = microtime(true) - $t1;
        $this->info('End Batch process in ' . $t . ' s');
        $this->info('---End Batch---');
    }

    public function processMappingMail()
    {

        $dataSchedule = (new DtMailSchedule)->getSchedule($mailType = 1, $standard_id = self::RANKING);
        $contentBody = '';
        if ($dataSchedule->count()) {
            $paramCategoryRanking = [
                'order_rank' => 0,
                'limit'      => 8,
                'order_by'   => ['order_rank', 'asc'],
            ];
            $categotyRanking = (new MstCategoryLarge())->getCategoryRanking($paramCategoryRanking)->toArray();
            $dataProduct = [];
            foreach ($categotyRanking as $category) {
                $paramProduct = [
                    'category_lar_code' => $category['category_lar_code'],
                    'limit' => 3,
                    'order_by' => ['category_product_list_honten.kingaku', 'DESC']
                ];
                $productRanking = (new OcProduct())->getProductRanking($paramProduct)->toArray();
                $dataProduct[$category['category_lar_code']]['name_category'] = $category['category_lar_name'];
                $dataProduct[$category['category_lar_code']]['products']      = $productRanking;
            }
            $contentBody = $this->renderHtml($dataProduct);

            foreach ($dataSchedule as $key => $schedule) {
                $dataCustomer = (new OcCustomer)->getCustomerRanking($schedule)->toArray();
                foreach ($dataCustomer as $customer) {
                    $token = md5(uniqid('', true));
                    //Mail content
                    if ($schedule['mail_template_option'] == 0) {
                        $content = $schedule['mail_content_html'];
                    } else {
                        $content = $schedule['mail_content_text'];
                    }

                    //Process time send mail
                    $timeSend = $this->caculateTimeSent($schedule);
                    //End time send mail

                    $dataReplace             = $customer;
                    $dataReplace['token']    = $token;
                    $dataReplace['dataBody'] = $contentBody;
                    $dataReplace['dataDate'] = date('Y年m月d日', strtotime($timeSend));
                    $mailcontent = $this->replaceParam($dataReplace, $content, $schedule['mail_template_option']);
                    $mailcontent = str_replace('href="', 'href="' . route('url.process') . "/?token=" . $token . "&url=", $mailcontent);

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
                    $this->insertMailSent($dataSentMail, $customer);
                    //End insert data
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
     * @param   string  $token token aut
     * @return  [type]                [return description]
     */
    public function renderHtml($dataProduct)
    {
        $data = [
            'dataProduct' => $dataProduct,
            'param'            => '?utm_source=df_h&utm_medium=email&utm_campaign=ranking-mail&utm_content=html',
            'mall'             => 'biz',
        ];
        $html = view('admin.mail-template.sub-item.slot-data-body-ranking', $data)->render();
        return $html;
    }
}
