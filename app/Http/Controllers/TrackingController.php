<?php

/**
 * Tracking user read mail
 *
 * @package     App\Http\Controllers
 * @subpackage  TrackingController
 * @copyright   Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author      Naruto <naruto_vn@monotos.biz>
 */

namespace App\Http\Controllers;

use App\Models\Biz\DtMailSent;
use App\Models\Df\DtMailSent as DtMailSentDf;
use App\Models\Biz\OcProduct;
use App\Models\Df\OcProduct as OcProductDf;
use App\Models\Biz\OcReview;
use App\Models\Df\OcReview as OcReviewDf;
use DB;
use App\Mail\AdminMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;

class TrackingController extends Controller
{
    use Notifiable;
    public function __construct()
    {
        //
    }

    /**
     * Tracking open
     *
     * @return void
     */
    public function imageTracking() {
        $token = request('token');
        $mall = request('mall');
        $this->processDataOpen($token, $mall);
        $base64_img_string = 'R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=';
        $raw_image_string = base64_decode($base64_img_string);
        return response($raw_image_string)->header('Content-Type', 'image/gif');
    }


    /**
     * Tracking clicked
     *
     * @return void
     */
    public function urlTracking() {
        //Khong the su dung request('url') vi se mat param cua url
        $fullUrl = urldecode(url()->full());
        $url = explode('url=', $fullUrl)[1]??'';

        $token = request('token');
        $mall = request('mall');
        if($token) {
            $this->processDataClick($token, $mall);
        }
        if($url) {
            return redirect($url);
        }
        $request = request();
        $url = '';
        $ip  = '';
        if (!empty($request)) {
            $url = $request->fullUrl();
            $ip  = $request->ip();
        }
        abort(404);
    }

    /**
     * Process open
     *
     * @param [type] $token
     * @return void
     */
    public function processDataOpen($token, $mall = 'biz') {
        if(!$token) {
            return;
        }
        if($mall === 'df') {
            (new DtMailSentDf)->isOpen($token);
        } else {
            (new DtMailSent)->isOpen($token);
        }
    }

    /**
     * Process clicked
     *
     * @param [type] $token
     * @return void
     */
    public function processDataClick($token, $mall = 'biz') {
        if(!$token) {
            return ;
        }

        if($mall == 'df') {
            (new DtMailSentDf)->isClicked($token);
        } else {
            (new DtMailSent)->isClicked($token);
        }
    }


    /**
     * Process Rating Product by customer
     *
     * @param [type] $token
     * @return void
     */
    public function ratingProduct()
    {
        $input    = request()->all();
        $token    = $input['token'] ?? '';
        $mall    = $input['mall'] ?? '';
        if(!$token) {
            return abort(404);
        }

        if($mall == 'df') {
            $modelMS  = new DtMailSentDf();
            $modelP   = new OcProductDf();
            $modelR   = new OcReviewDf();
        } else {
            $modelMS  = new DtMailSent();
            $modelP   = new OcProduct();
            $modelR   = new OcReview();
        }

        $dataCheck = $modelMS->getDataCheckReview($token);

        if (empty($dataCheck)) {
            return abort(404);
        } else {
            $customerId   = $dataCheck->customer_id;
            $arrProduct   = $input['product_id'] ?? [];
            $dataSendMail = [];
            if (count($arrProduct)) {
                $arrOV = [];
                foreach ($arrProduct as $value) {
                    if (!empty($input['customer_note_'.$value])) {
                        $customerName = $dataCheck->customer_name;
                        if (!empty($input['customer_name_'.$value])) {
                            $customerName = $input['customer_name_'.$value];
                        }

                        $arrOV[] = [
                            'product_id'    => $value,
                            'customer_id'   => $customerId,
                            'author'        => $customerName,
                            'rating'        => $input['rating_'.$value] ?? 0,
                            'text'          => $input['customer_note_'.$value] ?? '',
                            'status'        => 1,
                            'date_added'    => now(),
                            'date_modified' => now(),
                        ];

                        $dataSendMail[] = [
                            'product_id'   => $value,
                            'product_name' => $input['product_name_'.$value] ?? '',
                            // 'customer_id'  => $customerId,
                            'model'        => "",
                            'author'       => $customerName,
                            'rating'       => $input['rating_'.$value] ?? 0,
                            'text'         => $input['customer_note_'.$value] ?? ''
                        ];
                    }
                }
                /*insert data oc_review*/
                if (count($arrOV)) {
                    $modelR->insert($arrOV);
                }

                /*get data product*/
                $dataProduct = $modelR->getDataProduct($arrProduct);
                if (count($dataProduct)) {
                    foreach ($dataProduct as $product) {
                        $arrUpdate = [
                            'rated'      => ceil($product->avg_rating),
                            'rated_time' => $product->count_product,
                        ];
                        $arrWhere = [
                            'product_id' => $product->product_id
                        ];
                        /*update oc_product*/
                        $modelP->updateData($arrWhere, $arrUpdate);
                    }
                }
            }
            /*update data dt_mail_sent*/
            $arrUpMail = ['is_review' => 1];
            $arrWhMail = ['index' => $dataCheck->index];
            $modelMS->updateData($arrWhMail, $arrUpMail);

            /*send mail admin*/
            if (count($dataSendMail)) {
                $subject  = 'Rating product by customer';
                $mailTo   = str_replace(' ', '', config('common.mail_to_report_review'));
                if (config('app.env') !== 'production') {
                    $mailTo = crm_config('email_test');
                } else {
                    $mailTo = crm_config('email_admin');
                }
                if (!empty($mailTo)) {
                    $view     = 'admin.mail-template.mail-report-admin';
                    $sendMail = Mail::to($mailTo);
                    $sendMail->send(new AdminMailable($dataSendMail, $subject, $view));
                }
            }
        }

        return view('admin.mail-template.sub-item.review-success');
    }

    /**
     * Process Rating Product by customer (method post)
     *
     * @param [type] $token
     * @return void
     */
    public function reviewProduct()
    {
        $input    = request()->all();
        $token    = $input['token'] ?? '';
        $mall    = $input['mall'] ?? '';
        if (empty($token) || empty($mall)) {
            return abort(404);
        }

        if ($mall == 'df') {
            $modelMS  = new DtMailSentDf();
            $modelP   = new OcProductDf();
            $modelR   = new OcReviewDf();
        } else {
            $modelMS  = new DtMailSent();
            $modelP   = new OcProduct();
            $modelR   = new OcReview();
        }

        $dataCheck = $modelMS->getDataCheckReview($token);

        if (empty($dataCheck)) {
            return abort(404);
        } else {
            $customerId   = $dataCheck->customer_id;
            $arrProduct   = $input['product_id'] ?? [];
            $dataSendMail = [];
            if (count($arrProduct)) {
                $arrOV = [];
                foreach ($arrProduct as $value) {
                    if (!empty($input['customer_note_'.$value])) {
                        $customerName = $dataCheck->customer_name;
                        if (!empty($input['customer_name_'.$value])) {
                            $customerName = $input['customer_name_'.$value];
                        }

                        $arrOV[] = [
                            'product_id'    => $value,
                            'customer_id'   => $customerId,
                            'author'        => $customerName,
                            'rating'        => $input['rating_'.$value],
                            'text'          => $input['customer_note_'.$value],
                            'status'        => 1,
                            'date_added'    => now(),
                            'date_modified' => now(),
                        ];

                        $dataSendMail[] = [
                            'product_id'   => $value,
                            'product_name' => $input['product_name_'.$value],
                            // 'customer_id'  => $customerId,
                            'model'        => $input['model_'.$value],
                            'author'       => $customerName,
                            'rating'       => $input['rating_'.$value],
                            'text'         => $input['customer_note_'.$value]
                        ];
                    }
                }
                /*insert data oc_review*/
                if (count($arrOV)) {
                    $modelR->insert($arrOV);
                }

                /*get data product*/
                $dataProduct = $modelR->getDataProduct($arrProduct);
                if (count($dataProduct)) {
                    foreach ($dataProduct as $product) {
                        $arrUpdate = [
                            'rated'          => ceil($product->avg_rating),
                            'rated_time'     => $product->count_product,
                            'elastic_status' => 0,
                        ];
                        $arrWhere = [
                            'product_id' => $product->product_id
                        ];
                        /*update oc_product*/
                        $modelP->updateData($arrWhere, $arrUpdate);
                    }
                }
            }
            /*update data dt_mail_sent*/
            $arrUpMail = ['is_review' => 1];
            $arrWhMail = ['index' => $dataCheck->index];
            $modelMS->updateData($arrWhMail, $arrUpMail);

            /*send mail admin*/
            if (count($dataSendMail)) {
                $subject  = 'Rating product by customer';
                $mailTo   = str_replace(' ', '', config('common.mail_to_report_review'));
                if (config('app.env') !== 'production') {
                    $mailTo = crm_config('email_test');
                } else {
                    $mailTo = crm_config('email_admin');
                }
                if (!empty($mailTo)) {
                    $view     = 'admin.mail-template.mail-report-admin';
                    $sendMail = Mail::to($mailTo);
                    $sendMail->send(new AdminMailable($dataSendMail, $subject, $view));
                }
            }
        }

        return view('admin.mail-template.sub-item.review-success');
    }
}
