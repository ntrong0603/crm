<?php

/**
 * Customer Shift Controller
 *
 * @package     App\Http\Controllers
 * @subpackage  CustomerMoveRateController
 * @copyright   Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author      Sesshomaru <sesshomaru_vn@monotos.biz>
 */

namespace App\Http\Controllers;

use App\Models\Biz\MstCustomerStatistics;
use App\Models\Df\MstCustomerStatistics as MstCustomerStatisticsDf;
use Illuminate\Http\Request;

class CustomerMoveRateController extends Controller
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

        //Last update
        $lastDay = $this->mCustStat->select(['up_date'])->orderBy("up_date", 'desc')->first();
        if (!empty($lastDay)) {
            $date          = date_create($lastDay->up_date);
            $updateLastDay = date_format($date, "Y年m月d日 H時i分s秒");
        } else {
            $updateLastDay = '';
        }
        return view('admin.customer-move-rate.index', [
            'minDate'       => $minDate,
            'maxDate'       => $maxDate,
            'updateLastDay' => $updateLastDay,
        ]);
    }

    /**
     * Process get data customer shift
     *
     * @param Request $request condition customer shift need get data
     * @return json
     */
    public function getData(Request $request)
    {
        //Get data for chart
        $dataChart = array();
        $param = $request->all();
        //get list monthly
        $param['colRaw'] = "DISTINCT(monthly)";
        $arrMonthly = $this->mCustStat->getData($param);
        $param['colRaw'] = '';
        //get dataChart customer shift rank 3
        $param['rank_id'] =  3;
        $rank3 = $this->mCustStat->getData($param);
        $dataChart[] = $this->mapData($arrMonthly, $rank3);
        //get dataChart customer shift rank 2
        $param['rank_id'] =  2;
        $rank2 = $this->mCustStat->getData($param);
        $dataChart[] = $this->mapData($arrMonthly, $rank2);
        //get dataChart customer shift rank 1
        $param['rank_id'] =  1;
        $rank1 = $this->mCustStat->getData($param);
        $dataChart[] = $this->mapData($arrMonthly, $rank1);
        // end get data for chart

        //get data for table
        //Conditition get data for table rank
        $paramDateTable               = $request->all();
        $paramDateTable['limit']      = 13;
        $paramDateTable['colRaw']     = "DISTINCT(monthly)";
        $paramDateTable['orderByRaw'] = 'monthly DESC';

        //get date with condition
        $getDate = array_reverse($this->mCustStat->getData($paramDateTable)->toArray());
        $arrayColumnDate = [];
        foreach ($getDate as $item) {
            $arrayColumnDate[] = $item['monthly'];
        }

        $paramDateTable['date']       = $arrayColumnDate;
        $paramDateTable['colRaw']     = "";
        $paramDateTable['orderByRaw'] = "";
        //get data rank1
        $paramDateTable['rank_id'] =  1;
        $getDateRank1 = $this->mapData($getDate, $this->mCustStat->getData($paramDateTable));
        $rank1 = [];
        foreach ($getDateRank1 as $item) {
            $rank1[] = $item;
        }

        $paramDateTable['rank_id'] =  2;
        $getDateRank2 = $this->mapData($getDate, $this->mCustStat->getData($paramDateTable));
        $rank2 = [];
        foreach ($getDateRank2 as $item) {
            $rank2[] = $item;
        }

        $paramDateTable['rank_id'] =  3;
        $getDateRank3 = $this->mapData($getDate, $this->mCustStat->getData($paramDateTable));
        $rank3 = [];
        foreach ($getDateRank3 as $item) {
            $rank3[] = $item;
        }
        //end get data for table

        return response()->json([
            'dataChart' => $dataChart,
            'colDate'   => $arrayColumnDate,
            'rank1'     => $rank1,
            'rank2'     => $rank2,
            'rank3'     => $rank3,
        ]);
    }

    /**
     * Process check missing date and format data rank
     *
     * @param array $monthly array date
     * @param object $rank data rank
     * @return array
     */
    public function mapData($monthly, $rank)
    {
        $data = array();
        $checkNullRank = [];
        //check date missing in date rank
        foreach ($monthly as $key => $item) {
            $check = true;
            foreach ($rank as $value) {
                if ($value->monthly == $item['monthly']) {
                    $check = false;
                    break;
                }
            }
            if ($check) {
                $checkNullRank[] = ["key" => $key, "monthly" => $item['monthly']];
            }
        }

        //Process data rank, format monthly
        foreach ($rank as $item) {
            $formatDate = substr($item->monthly, 0, 4) . '-' . substr($item->monthly, -2);
            $data[] = [$formatDate, $item->transition_rate];
        }

        //Add value default for date missing in rank
        foreach ($checkNullRank as $item) {
            $formatDate = substr($item['monthly'], 0, 4) . '-' . substr($item['monthly'], -2);
            array_splice($data, $item['key'], 0, [[$formatDate, 0]]);
        }
        return $data;
    }
}
