<?php

/**
 * MailEffectMeaController
 *
 * @package    App\Http\Controllers
 * @subpackage MailEffectMeaController
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Pham Son<songoku_vn@monotos.biz>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biz\DtMailSetting;
use App\Models\Df\DtMailSetting as DtMailSettingDf;
use Illuminate\Support\Facades\Auth;

class MailEffectMeaController extends Controller
{
    private $mMailSetting;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mMailSetting = new DtMailSettingDf();
            } else {
                $this->mMailSetting = new DtMailSetting();
            }
            return $next($request);
        });
    }
    /**
     * Show the list the product
     * @return View
     */
    public $pagingNumber = 20;

    public function index()
    {
        return view('admin.mail-effect-measurement.list');
    }

    public function getListData(Request $request)
    {
        $limit = $request->get('perPage');
        if (!is_numeric($limit)) {
            $limit = 10;
        }

        $arrSearchs = [
            'searchText'            => $request->input('searchText', null),
            'search_last_date_from' => $request->input('search_last_date_from', null),
            'search_last_time_from' => $request->input('search_last_time_from', null),
            'search_last_date_to'   => $request->input('search_last_date_to', null),
            'search_last_time_to'   => $request->input('search_last_time_to', null),
            'customer_id'           => $request->input('customer_id', null),
            'customer_name'         => $request->input('customer_name', null),
            'customer_mail'         => $request->input('customer_mail', null),
            'mail_type'             => $request->input('mail_type', null),
        ];

        $arrSort = [];
        if (!empty($request->input('sort'))) {
            $sort = $request->input('sort');
            if (count($sort) >= 2 && count($sort) <= 3 && in_array($sort[1], ['asc', 'desc'])) {
                if (!empty($sort[2])) {
                    $arrSort = [$sort[2] . "." . str_replace('sort_', '', $sort[0]) => $sort[1]];
                } else {
                    $arrSort = [str_replace('sort_', '', $sort[0]) => $sort[1]];
                }
            }
        }
        // $arrSort = [
        //     'mail_subject'    => $request->input('sort_mail_subject', null),
        //     'mail_type'       => $request->input('sort_mail_type', null),
        //     'sent_date'       => $request->input('sort_sent_date', null),
        //     'total_sent'      => $request->input('sort_total_sent', null),
        //     'send_error_num'  => $request->input('sort_send_error_num', null),
        //     'open_num'        => $request->input('sort_open_num', null),
        //     'open_percent'    => $request->input('sort_open_percent', null),
        //     'clicked_num'     => $request->input('sort_clicked_num', null),
        //     'clicked_percent' => $request->input('sort_clicked_percent', null),
        // ];

        $datas = $this->mMailSetting->getDataListMail($arrSearchs, $arrSort, $limit);
        $mailType = config('common.mail_setting.mail_type');
        if ($datas->count()) {
            foreach ($datas as $item) {
                if (!empty($item->mail_type)) {
                    $item->mail_type = $mailType[$item->mail_type];
                }
            }
        }
        return response($datas);
    }
}
