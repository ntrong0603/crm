<?php
/**
 * Mapping dữ liệu dt_mail_sent từ email setting và email schedule
 *
 * @package App\Console
 * @copyright Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author Naruto<naruto_vn@monotos.biz>
 */
namespace App\Console;

use Illuminate\Console\Command;
use App\Models\Biz\DtMailSent;
use App\Models\Biz\DtMailSentContent;
use Illuminate\Support\Carbon;

class ProcessBizCommand extends Command
{

    const BUYFIRST  = '1';
    const BUYLAST   = '2';
    const BIRTHDAY  = '3';
    const LASTSHIP  = '4';
    const CART      = '5';
    const WISHLIST  = '6';
    const LASTVIEW  = '7';
    const RECOMMEND = '8';
    const RANKING   = '9';

    /**
     * Replace param to data from content
     *
     * @param array $data
     * @param string $content
     * @param int $option 0- html, 1 - text
     * @return void
     */
    public function replaceParam(array $data, string $content, $option = 0) {

        $arrParam = [
            '[shop_name]'                => 'DIY FACTORY (B向け本店)',
            '[shop_address]'             => $data['shop_address'] ?? '',
            '[shop_shop_url]'            => 'https://shop.diyfactory.jp',
            '[shop_tel]'                 => '',
            '[customer_org_id]'          => $data['customer_id'] ?? '',
            '[customer_name]'            => $data['firstname'].$data['lastname'],
            '[customer_last_name]'       => $data['lastname'] ?? '',
            '[customer_first_name]'      => $data['firstname'] ?? '',
            '[customer_last_name_kana]'  => $data['firstname_kana'] ?? '',
            '[customer_first_name_kana]' => $data['lastname_kana'] ?? '',
            '[customer_sex]'             => $data['sex'] ?? 0,
            '[customer_sex:format]'      => '',
            '[customer_birthday]'        => $data['birthday'] ?? '',
            '[customer_email]'           => $data['email'] ?? '',
            '[customer_email_mobile]'    => $data['telephone'] ?? '',
            '[customer_fax]'             => $data['fax'] ?? '',
            '[customer_zip]'             => $data['postcode'] ?? '',
            '[data-body]'                => $data['dataBody'] ?? '',
            '[data-token]'               => $data['token'] ?? '',
            '[data-date]'                => $data['dataDate'] ?? '',
            '[data-mall]'                => $data['mall'] ?? '',
        ];

        foreach ($arrParam as $key => $v) {
            $content = str_replace($key, $v, $content);
        }
        if ($option == 1) {
            // mail text
            $content = preg_replace( "/\r|\n/", "<br>", $content);
        }
        return $content;
    }

    /**
     * Insert data to dt_mail_sent
     *
     * @param   [array]  $schedule
     * @param   [array]  $customer
     * @param   [string]  $mailcontent
     * @param   [string]  $subject
     * @param   [string]  $token
     * @param   [datetime]  $timeSend
     *
     * @return  [type]                [return description]
     */
    public function insertMailSent($dataSentMail, $customer, $action = "") {

        if($this->checkDataSentExist($action, $dataSentMail['schedule_id'], $customer['customer_id'])) {
            return;
        }
        $dataMap = [
            'mail_setting_id' => $dataSentMail['mail_setting_id'],
            'schedule_id'     => $dataSentMail['schedule_id'],
            'customer_id'     => $customer['customer_id'],
            'mail_from'       => $dataSentMail['mail_from'],
            'mail_from_name'  => $dataSentMail['mail_from_name'],
            'customer_name' => $customer['firstname'].$customer['lastname'],
            'mail_subject'  =>  $dataSentMail['subject'],
            'mail_to'         => $customer['email'],
            'send_timing'     => $dataSentMail['timeSend'],
            'send_status'     => 0,
            'token'           => $dataSentMail['token'],
            'in_ope_cd'       => $dataSentMail['in_ope_cd'] ?? 1,
            'in_date'         => date('Y-m-d H:i:s'),
            'up_date'         => date('Y-m-d H:i:s'),
        ];
        $id = (new DtMailSent)->insertGetId($dataMap);
        if($id) {
            $dataContent = [
                'mail_index'    => $id,
                'mail_content'  => $dataSentMail['mailcontent'],
            ];
            DtMailSentContent::insert($dataContent);
        }

    }

    /**
     * Caculate time will send mail
     *
     * @param [type] $data
     * @return void
     */
    public function caculateTimeSent($data) {
        $hour = $data['hour'];
        $minute = $data['minute'];
        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 00:00:00');
        $endTime->add($hour, 'hour');
        $endTime->add($minute, 'minute');
        $time = $endTime->format('Y-m-d H:i:s');
        return $time;
    }

    /**
     * Caculate time will send mail cart
     *
     * @return void
     */
    public function caculateTimeSentCart() {
        return Carbon::now()->format('Y-m-d H:i:s');
    }

    /**
     * Check data dt_mail_sent exist
     * Mỗi customer sẽ chỉ nhận 1 email của 1 loại schedule trong ngày 
     *
     * @param   [type]  $action       [$action description]
     * @param   [type]  $schedule_id  [$schedule_id description]
     * @param   [type]  $customer_id  [$customer_id description]
     *
     * @return  [type]                [return description]
     */
    public function checkDataSentExist($action, $schedule_id, $customer_id) {
        // if($action == 'cart') {
        //     return false;
        // }
        return DtMailSent::where('schedule_id', $schedule_id)
            ->where('customer_id', $customer_id)
            ->whereDate('send_timing', date('Y-m-d'))
            ->first();
    }

}
