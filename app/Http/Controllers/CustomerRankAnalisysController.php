<?php

/**
 * Customer Rank Analisys Controller
 *
 * @package     App\Http\Controllers
 * @subpackage  CustomerRankAnalisysController
 * @copyright   Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author      Sesshomaru <sesshomaru_vn@monotos.biz>
 */

namespace App\Http\Controllers;

use App\Models\Biz\MstCustomerStatistics;
use App\Models\Df\MstCustomerStatistics as MstCustomerStatisticsDf;
use Illuminate\Http\Request;

class CustomerRankAnalisysController extends Controller
{
    private $mCustStat;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mCustStat = new MstCustomerStatisticsDf();
            } else {
                $this->mCustStat = new MstCustomerStatistics();
            }
            return $next($request);
        });

    }

    /**
     * View screen customer shift
     *
     * Return view, min date and max date bar range date
     * @return view
     */
    public function index(Request $request)
    {
        // Min and max date rank
        $getData = $this->mCustStat->getDateMinMax();
        $minDate = $getData['mi'] ?? date("Y-m");
        $maxDate = $getData['ma'] ?? date("Y-m");

        if ($minDate < "2016-01") {
            $minDate = "2016-01";
        }

        //check param get rank month
        if (!empty($request->get('rankMonthe'))) {
            $monthly = $request->get('rankMonthe');
        } elseif ($getData['ma']) {
            $date = date_create($getData['ma']);
            $monthly = date_format($date, "Ym");
        } else {
            $monthly =  date("Ym");
        }

        //Max xaxis
        $maxMon = $this->mCustStat->select(['mean_stay_priod'])->orderBy("mean_stay_priod", 'desc')->first();
        if (!empty($maxMon)) {
            $maxMon =  ceil($maxMon->mean_stay_priod) + 1;
        } else {
            $maxMon = 30;
        }

        //Max yaxis
        $maxAccum = $this->mCustStat->select(['mean_price_cum'])->orderBy("mean_price_cum", 'desc')->first();
        if (!empty($maxAccum)) {
            $maxAccum = ceil($maxAccum->mean_price_cum) + 1;
        } else {
            $maxAccum = 95;
        }

        //Max customer use caculate radius for element in chart
        $maxCustomer = $this->mCustStat->select(['customer_number'])->orderBy("customer_number", 'desc')->first();
        if (!empty($maxCustomer)) {
            $maxCustomer = $maxCustomer->customer_number;
        } else {
            $maxAccum = 6492;
        }

        //get last update database
        $lastDay = $this->mCustStat->select(['up_date'])->orderBy("up_date", 'desc')->first();
        if (!empty($lastDay)) {
            $date = date_create($lastDay->up_date);
            $updateLastDay = date_format($date, "Y年m月d日 H時i分s秒");
        } else {
            $updateLastDay = '';
        }
        return view(
            'admin.customer-rank-analisys.index',
            [
                'minDate' => $minDate,
                'maxDate' => $maxDate,
                'maxMon' => $maxMon,
                'maxAccum' => $maxAccum,
                'maxCustomer' => $maxCustomer,
                'monthly' => $monthly,
                'updateLastDay' => $updateLastDay,
            ],
        );
    }

    /**
     * Process get data customer rank
     *
     * @param Request $request condition customer rank need get data
     * @return json
     */
    public function getData(Request $request)
    {
        $data = array();
        $param = $request->all();
        //get list monthly
        $param['col'] = [
            'rank_id',
            'mean_stay_priod',
            'mean_price_cum',
            'customer_number',
            'total_price_cum',
            'price_rate',
            'customer_number_rate',
            'customer_number_total',
            'customer_number_total_rate',
        ];
        if (empty($param['monthly'])) {
            $param['monthly'] = date("Ym");
        }
        $param['orderByRaw'] = "rank_id desc";
        //info rank customer 現役
        $param['arr_rank_id'] = [1, 2, 3, 4, 5];
        $group1 = $this->mCustStat->getData($param)->toArray();
        //info rank customer 離脱
        $param['arr_rank_id'] = [6, 7, 8, 9, 10];
        $group2 = $this->mCustStat->getData($param)->toArray();
        if (!empty($group1) || !empty($group2)) {
            $data[] = $group1;
            $data[] = $group2;
        }
        return response()->json([
            'data'   => $data,
            'group1' => $group1,
            'group2' => $group2,
        ]);
    }
}
