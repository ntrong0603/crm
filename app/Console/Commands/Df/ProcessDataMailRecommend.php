<?php

/**
 * Mapping dữ liệu dt_mail_sent từ email setting và email schedule
 *
 * @package App\Console\Commands\Df
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Sesshomaru<sesshomaru_vn@monotos.biz>
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
use App\Models\Df\OcCart;
use App\Models\Df\OcOrderProduct;
use App\Models\Df\OcProduct;

class ProcessDataMailRecommend extends ProcessDfCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df_process:data-mail-recommed';

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
        event(new eventBatch(['status' => 'start']));
        $this->processMappingMail();
        //End event process batch
        event(new eventBatch(['status' => 'end']));
    }

    public function processMappingMail()
    {

        $dataSchedule = (new DtMailSchedule)->getSchedule($mailType = 1, $standard_id = self::RECOMMEND);
        $contentBody = '';
        if ($dataSchedule->count()) {
            foreach ($dataSchedule as $key => $schedule) {
                $dateRange = $schedule['date_num'];
                $date      = Carbon::now()->sub($dateRange, 'day')->format('Y-m-d');
                $customers = (new OcCustomer)->getCustomerLastBuyRecommend($date, $schedule);
                $customers = $customers->toArray();
                foreach ($customers as $key => $customer) {
                    $arrProductExist = json_decode($customer['product_recommend'], true) ?? [];
                    $products = (new OcOrderProduct)->getProductRecommend($customer['customer_id'], $arrProductExist,  $date);
                    if (!count($products)) {
                        continue;
                    }
                    $token = md5(uniqid('', true));

                    //Cập nhật những sản phẩm recommend đã gửi
                    $newRecommend = collect($products)->pluck('model')->toArray();
                    $arrayProductSent = array_merge($arrProductExist, $newRecommend);
                    OcCustomer::where('customer_id', $customer['customer_id'])->update(['product_recommend' => $arrayProductSent]);
                    //end

                    $contentBody = $this->renderHtml($products, $token);

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
    public function renderHtml($dataProduct, $token = '')
    {
        $data = [
            'dataProduct' => $dataProduct,
            'param'            => '?utm_source=df_h&utm_medium=email&utm_campaign=recommend-mail&utm_content=html',
            'mall'             => 'df',
        ];
        $html = view('admin.mail-template.sub-item.slot-data-body-recommend', $data)->render();
        return $html;
    }

    /**
     * Get list product exist
     *
     * @param   [int]  $cId         [$cId description]
     * @param   [int]  $scheduleId  [$scheduleId description]
     *
     * @return  [type]               [return description]
     */
    public function getProductListExist($cId, $scheduleId)
    {
        $data = [];
        $dateAgo30day = Carbon::now()->sub('30', 'day')->format('Y-m-d');
        $dateAgo60day = Carbon::now()->sub('60', 'day')->format('Y-m-d');
        $dataSent = (new DtMailSent)
            ->where('schedule_id', $scheduleId)
            ->where('customer_id', $cId)
            ->whereNotNull('product_list')
            ->where(function ($query) use ($dateAgo60day, $dateAgo30day) {
                $query->whereDate('send_timing', $dateAgo30day)
                    ->orWhereDate('send_timing', $dateAgo60day);
            })
            ->pluck('product_list')
            ->toArray();
        if (count($dataSent)) {
            foreach ($dataSent as $row) {
                $data = array_merge($data, explode(',', $row));
            }
            $data = array_unique($data);
        }
        return $data;
    }
}
