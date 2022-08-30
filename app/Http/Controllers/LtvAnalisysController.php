<?php

/**
 * LTV Analisys Controller
 *
 * @package     App\Http\Controllers
 * @subpackage  LtvAnalisysController
 * @copyright   Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author      Sesshomaru <sesshomaru_vn@monotos.biz>
 */

namespace App\Http\Controllers;

use App\Models\Biz\MstCustomerStatistics;
use App\Models\Df\MstCustomerStatistics as MstCustomerStatisticsDf;
use Illuminate\Http\Request;

class LtvAnalisysController extends Controller
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
    public function index()
    {
        $getData = $this->mCustStat->getDateMinMax();
        $minDate = $getData['mi'] ?? date("Y-m");
        $maxDate = $getData['ma'] ?? date("Y-m");

        if ($minDate < "2016-01") {
            $minDate = "2016-01";
        }

        return view(
            'admin.ltv-analisys.index',
            [
                'minDate' => $minDate,
                'maxDate' => $maxDate,
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
        $getDate = $this->mCustStat->getDateMinMax();
        $maxDate = date_create($getDate['ma']);
        $maxDate = date_format($maxDate, "Ym");
        //Data table
        $paramTable['col'] = [
            'monthly',
            'rank_id',
            'one_year_ltv',
            'cum_mean_price',
            'customer_number',
        ];

        $paramTable['arr_rank_id'] = [1, 2, 3, 4, 5];
        $paramTable['date']        = [$maxDate];
        $paramTable['orderByRaw']  = "rank_id desc";
        $paramTable['limit']       = 5;
        $dataTable = $this->mCustStat->getData($paramTable)->toArray();
        $result = [
            'dataTable' => $dataTable,
        ];

        return response()->json($result);
    }

    public function getDataChart(Request $request)
    {

        //data chart
        $paramChart = $request->all();
        // get array month
        $paramMonth = $request->all();
        $paramMonth['col'] = ['monthly'];
        $paramMonth['group_by'] = 'monthly';

        $arrMonth = $this->mCustStat->getData($paramMonth);


        $paramChart['col'] = [
            'monthly',
            'one_month_ltv',
        ];
        $dataChart = [];
        // Data rank 1
        $paramChart['arr_rank_id'] = [1];
        $dataChart['rank1'] = $this->mCustStat->getData($paramChart)->toArray();
        // Data rank 2
        $paramChart['arr_rank_id'] = [2];
        $dataChart['rank2'] = $this->mCustStat->getData($paramChart)->toArray();
        // Data rank 3
        $paramChart['arr_rank_id'] = [3];
        $dataChart['rank3'] = $this->mCustStat->getData($paramChart)->toArray();
        // Data rank 4
        $paramChart['arr_rank_id'] = [4];
        $dataChart['rank4'] = $this->mCustStat->getData($paramChart)->toArray();
        // Data rank 5
        $paramChart['arr_rank_id'] = [5];
        $dataChart['rank5'] = $this->mCustStat->getData($paramChart)->toArray();
        // 離脱した人数 customer_number_not_good
        // data people 1
        $paramChart['col'] = [
            'monthly',
            'transition_num',
        ];
        $paramChart['arr_rank_id'] = [2];
        $dataChart['people1'] = $this->mCustStat->getData($paramChart)->toArray();
        // 新規から入門への推移人数 => transition_num
        // data people 2

        $paramChart['col'] = [
            'monthly',
            'customer_number_not_good',
        ];
        $paramChart['arr_rank_id'] = [5];
        $dataChart['people2'] = $this->mCustStat->getData($paramChart)->toArray();
        return response()->json(['dataChart' => $dataChart, 'arrMonth' => data_get($arrMonth, '*.monthly')]);
    }
}
