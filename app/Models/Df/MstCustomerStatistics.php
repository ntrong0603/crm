<?php

/**
 * Model for mst_customer_statistics table.
 *
 * @package    App\Models\Df
 * @subpackage MstCustomerStatistics
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Df\OcCustomer;
use Carbon\Carbon;

class MstCustomerStatistics extends Model
{
    protected $connection = 'df';
    protected $table      = 'mst_customer_statistics';
    const CREATED_AT      = 'in_date';
    const UPDATED_AT      = 'up_date';
    protected $guarded    = [];
    protected $dbCon = '';
    protected static $dataThreshold;

    public function __construct()
    {
        $this->dbCon = DB::connection($this->connection);
    }

    public static function getDataThreshold()
    {
        if (self::$dataThreshold === null) {
            self::$dataThreshold = (new MstCustomerThreshold)->getData();
        }
        return  self::$dataThreshold;
    }

    /**
     * @param  array condition $where
     * @param  array update $data
     * @return boolean
     */
    public function updateData($where, $data)
    {
        $result = $this->where($where)
            ->update($data);
        return $result;
    }

    /**
     * Process get data
     *
     * @param array $param array condition get data
     * @return object
     */
    public function getData($param = [])
    {
        $col = [
            'monthly',
            DB::raw('FORMAT(transition_rate, 1) as transition_rate'),
        ];

        if (!empty($param['colRaw'])) {
            $data = $this->selectRaw($param['colRaw']);
        } elseif (!empty($param['col'])) {
            $data = $this->select($param['col']);
        } else {
            $data = $this->select($col);
        }

        $data->where(function ($query) use ($param) {
            if (!empty($param['rank_id'])) {
                $query->where('rank_id', $param['rank_id']);
            }
            if (!empty($param['arr_rank_id'])) {
                $query->whereIn('rank_id', $param['arr_rank_id']);
            }
            if (!empty($param['fromDate'])) {
                $query->where('monthly', '>=', $param['fromDate']);
            }
            if (!empty($param['toDate'])) {
                $query->where('monthly', '<=', $param['toDate']);
            }
            if (!empty($param['date'])) {
                $query->whereIn('monthly', $param['date']);
            }
            if (!empty($param['monthly'])) {
                $query->where('monthly', $param['monthly']);
            }
            if (!empty($param['monthly_min'])) {
                $query->where('monthly', '>=', $param['monthly_min']);
            }
            if (!empty($param['monthly_max'])) {
                $query->where('monthly', '<=', $param['monthly_max']);
            }
        });

        if (!empty($param['orderByRaw'])) {
            $data->orderByRaw($param['orderByRaw']);
        } else {
            $data->orderBy('monthly', 'asc');
        }

        if (!empty($param['group_by'])) {
            $data->groupBy($param['group_by']);
        }

        if (!empty($param['limit'])) {
            $data->limit($param['limit']);
        }

        $data = $data->get();
        return $data;
    }

    /**
     * Caculate percent rate
     *
     * @param   [type]  $date  Y-m-d
     *
     * @return  [void]
     */
    public function caculatePercent($date = null)
    {
        $date = $date ? $date : date('Y-m-d');
        $currentMonth =  Carbon::createFromFormat('Y-m-d', $date)->format('Ym');
        $arrRankGood = ['1', '2', '3', '4', '5'];
        $arrRankNotGood = ['6', '7', '8', '9', '10'];

        $processGroupGood = $this
            ->selectRaw('SUM(total_price_cum) as sum_price, SUM(customer_number) as sum_number')
            ->whereIn('rank_id', $arrRankGood)
            ->where('monthly', $currentMonth)
            ->groupBy('monthly')
            ->first();

        $processGroupNotGood = $this
            ->selectRaw('SUM(total_price_cum) as sum_price, SUM(customer_number) as sum_number')
            ->whereIn('rank_id', $arrRankNotGood)
            ->where('monthly', $currentMonth)
            ->groupBy('monthly')
            ->first();

        if (!empty($processGroupGood['sum_price']) && !empty($processGroupGood['sum_number'])) {
            $sql = "UPDATE mst_customer_statistics
                SET
                    price_rate = ROUND((total_price_cum / " . $processGroupGood['sum_price'] . ") * 100),
                    customer_number_rate = ROUND((customer_number / " . $processGroupGood['sum_number'] . ") * 100),
                    up_ope_cd = 1
                WHERE
                    monthly = '" . $currentMonth . "' AND rank_id IN (" . implode(',', $arrRankGood) . ")
                ";
            // echo $sql;exit;
            $this->dbCon->update($sql);
        }

        if (!empty($processGroupNotGood['sum_price']) && !empty($processGroupNotGood['sum_number'])) {
            $sql = "UPDATE mst_customer_statistics
                SET
                    price_rate = ROUND((total_price_cum / " . $processGroupNotGood['sum_price'] . ") * 100),
                    customer_number_rate = ROUND((customer_number / " . $processGroupNotGood['sum_number'] . ") * 100),
                    up_ope_cd = 1
                WHERE
                    monthly = '" . $currentMonth . "'
                    AND
                    rank_id IN (" . implode(',', $arrRankNotGood) . ")
                ";
            // echo $sql;exit;
            $this->dbCon->update($sql);
        }
    }

    /**
     * customer rank analysis calculate
     *
     * @param   [type]$date  Y-m-d
     *
     * @return  [void]
     */
    public function calculateStatic($date = null)
    {
        //Delete old data
        $nowMonth = date('Ym');
        $this->where('monthly', $nowMonth)->delete();
        //end delete old data


        $date = $date ? $date : date('Y-m-d');
        // insert Rank A
        $priod_to_secession = $this->getDataThreshold()['priod_to_secession'] ?? 0;
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    1,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 1 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) < ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);

        // insert Rank B
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    2,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 2 AND  DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) < ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);

        // insert Rank C
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    3,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 3 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) < ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);

        // insert Rank D
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    4,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 4 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) < ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);

        // insert Rank E
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    5,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 5 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) < ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);


        // Rank A Secession
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    6,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 1 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) >= ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);

        // Rank B Secession
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    7,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 2 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) >= ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);


        // Rank C Secession
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    8,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 3 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) >= ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);

        // Rank D Secession
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    9,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 4 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) >= ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);


        // Rank E Secession
        $sql = "INSERT IGNORE INTO mst_customer_statistics
                    SELECT
                    DATE_FORMAT(?, '%Y%m'),
                    10,
                    AVG(DATEDIFF(a.last_buy_date, a.first_buy_date))/30,
                    AVG(a.buy_total)/10000,
                    COUNT(*),
                    0,
                    SUM(a.buy_total)/10000,
                    0,0,0,0,0,0,0,0,0,0,0,now(),now(),1,1
                    FROM oc_customer as a
                    where a.customer_rank = 5 AND DATE_FORMAT(a.date_added, '%Y-%m-%d') <= ?
                    AND DATEDIFF(?, a.last_buy_date) >= ?
                    GROUP BY a.customer_rank
            ";
        $this->dbCon->insert($sql, [$date, $date, $date, $priod_to_secession]);

        //Update sum rank_id 6 > 10
        $nowMonth =  Carbon::createFromFormat('Y-m-d', $date)->format('Ym');
        $countRankNotgood = $this
            ->selectRaw('SUM(customer_number) as count')
            ->whereIn('rank_id', ['6', '7', '8', '9', '10'])
            ->where('monthly', $nowMonth)
            ->first();
        $this->where('monthly', $nowMonth)
            ->update([
                'customer_number_not_good' => $countRankNotgood->count,
                'up_ope_cd'                => 1
            ]);
    }

    /**
     * Transition Rate Calculate
     *
     * @param   [datetime]$date  Y-m-d
     *
     * @return  [type]       [return description]
     */
    public function calculateTransitionRate($date = null)
    {
        $date = $date ? $date : date('Y-m-d');
        $nowMonth =  Carbon::createFromFormat('Y-m-d', $date)->format('Ym');
        $preMonth = Carbon::createFromFormat('Ym', $nowMonth)->subMonth()->format('Ym');
        //Calculate Rate which Rank A move to B
        $rankA2B = null;
        $rankA2Bdata = $this->dbCon->table('oc_customer')
            ->selectRaw('COUNT(*) as count')
            ->where('customer_rank_old', 1)
            ->where('customer_rank', 2)
            ->whereDate('date_added', '<=', $date)
            ->groupBy('customer_rank')
            ->first();
        if ($rankA2Bdata) {
            $rankA2B = $rankA2Bdata->count;
        }

        $preMonthNumberA2B = null;
        $preMonthDataA2B = $this->dbCon->table('mst_customer_statistics')
            ->select('customer_number')
            ->where('monthly', $preMonth)
            ->where('rank_id', 1)
            ->first();
        if ($preMonthDataA2B) {
            $preMonthNumberA2B = $preMonthDataA2B->customer_number;
        }
        if ($rankA2B && $preMonthNumberA2B) {
            $rateA2B = ($rankA2B / $preMonthNumberA2B) * 100;
            $this->dbCon->table('mst_customer_statistics')
                ->where('monthly', $nowMonth)
                ->where('rank_id', 1)
                ->update(
                    [
                        'transition_rate' => $rateA2B,
                        'transition_num' => $rankA2B,
                        'up_ope_cd'      => 1
                    ]
                );
        }
        //End


        //Calculate Rate which Rank B move to C
        $rankB2C = null;
        $rankB2Cdata = $this->dbCon->table('oc_customer')
            ->selectRaw('COUNT(*) as count')
            ->where('customer_rank_old', 2)
            ->where('customer_rank', 3)
            ->whereDate('date_added', '<=', $date)
            ->groupBy('customer_rank')
            ->first();
        if ($rankB2Cdata) {
            $rankB2C = $rankB2Cdata->count;
        }

        $preMonthNumberB2C = null;
        $preMonthDataB2C = $this->dbCon->table('mst_customer_statistics')
            ->select('customer_number')
            ->where('monthly', $preMonth)
            ->where('rank_id', 2)
            ->first();
        if ($preMonthDataB2C) {
            $preMonthNumberB2C = $preMonthDataB2C->customer_number;
        }
        if ($rankB2C && $preMonthNumberB2C) {
            $rateB2C = ($rankB2C / $preMonthNumberB2C) * 100;
            $this->dbCon->table('mst_customer_statistics')
                ->where('monthly', $nowMonth)
                ->where('rank_id', 2)
                ->update(
                    [
                        'transition_rate' => $rateB2C,
                        'transition_num' => $rankB2C,
                        'up_ope_cd'      => 1
                    ]
                );
        }
        //End

        //Calculate Rate which Rank C move to D
        $rankC2D = null;
        $rankC2Ddata = $this->dbCon->table('oc_customer')
            ->selectRaw('COUNT(*) as count')
            ->where('customer_rank_old', 3)
            ->where('customer_rank', 4)
            ->whereDate('date_added', '<=', $date)
            ->groupBy('customer_rank')
            ->first();
        if ($rankC2Ddata) {
            $rankC2D = $rankC2Ddata->count;
        }

        $preMonthNumberC2D = null;
        $preMonthDataC2D = $this->dbCon->table('mst_customer_statistics')
            ->select('customer_number')
            ->where('monthly', $preMonth)
            ->where('rank_id', 3)
            ->first();
        if ($preMonthDataC2D) {
            $preMonthNumberC2D = $preMonthDataC2D->customer_number;
        }
        if ($rankC2D && $preMonthNumberC2D) {
            $rateC2D = ($rankC2D / $preMonthNumberC2D) * 100;
            $this->dbCon->table('mst_customer_statistics')
                ->where('monthly', $nowMonth)
                ->where('rank_id', 3)
                ->update(
                    [
                        'transition_rate' => $rateC2D,
                        'transition_num' => $rankC2D,
                        'up_ope_cd'      => 1
                    ]
                );
        }
        //End
    }


    /**
     * Get min, max of monthly
     */
    public function getDateMinMax()
    {
        $query = "DATE_FORMAT(CONCAT(MIN(monthly),'00'), '%Y-%m') AS mi,
        DATE_FORMAT(CONCAT(MAX(monthly),'00'), '%Y-%m') AS ma";
        $data = $this->selectRaw($query)
            ->get()
            ->toArray();
        $data = $data['0'] ?? [];
        return $data;
    }

    /**
     * Get dataa sild price
     */
    public function getDatasDashBoardSoldPrice($arrMonth)
    {
        return $this->whereIn('monthly', $arrMonth)
            ->groupBy('monthly')
            ->get(['monthly', 'new_sold_price', 'repeat_sold_price']);
    }
}
