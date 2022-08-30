<?php

/**
 * Screen dashboard
 *
 * @package    App\Http\Controllers
 * @subpackage DashBoardController
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Truong Nghia<truong.van.nghia@rivercrane.vn>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biz\MstCustomerStatistics;
use App\Models\Df\MstCustomerStatistics as MstCustomerStatisticsDf;

class DashBoardController extends Controller
{
    private $modelCS;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->modelCS = new MstCustomerStatisticsDf;
            } else {
                $this->modelCS = new MstCustomerStatistics;
            }
            return $next($request);
        });
    }
    
    /**
     * Process get data
     * @return $object json
     */
    public function getDatas()
    {
        $arrMonth     = [];
        $arrLastMonth = [];
        $arrStrMonth  = [];
        for ($i=11; $i >= 0; $i--) {
            $arrStrMonth[]  = (int)date('m', strtotime("-$i months")) . '月';
            $arrMonth[]     = date('Ym', strtotime("-$i months"));
            $arrLastMonth[] = date('Ym', strtotime("-1 year -$i months"));
        }
        $datasThisYearDB     = $this->modelCS->getDatasDashBoardSoldPrice($arrMonth);
        $datasLastYearDB     = $this->modelCS->getDatasDashBoardSoldPrice($arrLastMonth);
        $datasNewThisYear    = [];
        $datasRepeatThisYear = [];
        $datasNewLastYear    = [];
        $datasRepeatLastYear = [];
        foreach ($arrMonth as $month) {
            $datasNewThisYear[$month]    = 0;
            $datasRepeatThisYear[$month] = 0;
            foreach ($datasThisYearDB as $dataThis) {
                if ($month === $dataThis->monthly) {
                    $datasNewThisYear[$month]    = $dataThis->new_sold_price;
                    $datasRepeatThisYear[$month] = $dataThis->repeat_sold_price;
                }
            }
        }
        foreach ($arrLastMonth as $month) {
            $datasNewLastYear[$month]    = 0;
            $datasRepeatLastYear[$month] = 0;
            foreach ($datasLastYearDB as $dataLast) {
                if ($month === $dataLast->monthly) {
                    $datasNewLastYear[$month]    = $dataLast->new_sold_price;
                    $datasRepeatLastYear[$month] = $dataLast->repeat_sold_price;
                }
            }
        }

        $datasRankThisMonth = $this->modelCS->where('monthly', date('Ym'))->get(['rank_id', 'transition_num','customer_number']);
        $datasRankPrevMonth = $this->modelCS->where('monthly', date('Ym', strtotime("-1 months")))->get(['rank_id', 'transition_num','customer_number']);
        $currentCountLv1 = 0;
        $currentCountLv2 = 0;
        $currentCountLv3 = 0;
        $currentCountLv4 = 0;
        $currentCountLv5 = 0;
        $stayDiffLv1     = "±0人";
        $stayDiffLv2     = "±0人";
        $stayDiffLv3     = "±0人";
        $stayDiffLv4     = "±0人";
        $stayDiffLv5     = "±0人";
        foreach ([1, 2, 3, 4, 5] as $rank) {
            foreach ($datasRankThisMonth as $dataRank) {
                if ($dataRank->rank_id === $rank) {
                    ${"currentCountLv" . $rank} = $dataRank->customer_number;
                    $prevTrans = 0;
                    foreach ($datasRankPrevMonth as $dataRankPrev) {
                        if ($dataRankPrev->rank_id === $rank) {
                            $prevTrans = $dataRankPrev->transition_num;
                            break;
                        }
                    }
                    $minus = $dataRank->transition_num - $prevTrans;
                    ${"stayDiffLv" . $rank} = $minus === 0 ? "±0人" : ($minus > 0 ? ('+' . $minus . '人') : ($minus . '人'));
                    break;
                }

            }
        }
        return response()->json([
            'labels'              => $arrStrMonth,
            'datasNewThisYear'    => \Arr::flatten($datasNewThisYear),
            'datasRepeatThisYear' => \Arr::flatten($datasRepeatThisYear),
            'datasNewLastYear'    => \Arr::flatten($datasNewLastYear),
            'datasRepeatLastYear' => \Arr::flatten($datasRepeatLastYear),
            'currentCountLv1'     => $currentCountLv1,
            'currentCountLv2'     => $currentCountLv2,
            'currentCountLv3'     => $currentCountLv3,
            'currentCountLv4'     => $currentCountLv4,
            'currentCountLv5'     => $currentCountLv5,
            'stayDiffLv1'         => $stayDiffLv1,
            'stayDiffLv2'         => $stayDiffLv2,
            'stayDiffLv3'         => $stayDiffLv3,
            'stayDiffLv4'         => $stayDiffLv4,
            'stayDiffLv5'         => $stayDiffLv5,
        ]);
    }
}
