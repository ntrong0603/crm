<?php

/**
 * Model for oc_customer table.
 *
 * @package    App\Models\Biz
 * @subpackage OcCustomer
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class OcCustomer extends Model
{
    protected $primaryKey = 'customer_id';
    protected $connection = 'biz';
    protected $table      = 'oc_customer';
    const CREATED_AT      = 'date_added';
    const UPDATED_AT      = 'date_modified';
    protected $dbCon = '';
    protected $guarded    = [];
    public static $dataThreshold = null;

    public function __construct()
    {
        $this->dbCon = DB::connection($this->connection);
    }

    /**
     * Process update info customer
     *
     * @param  array $where condition
     * @param  array $data update
     * @return boolean
     */
    public function updateData($where, $data)
    {
        $result = $this->where($where)
            ->update($data);
        return $result;
    }

    /**
     * Process get list customer
     *
     * @param array $param condition get data
     * @param int $limit limit record need get
     * @return object
     */
    public function getData($param = '', $limit = 25)
    {
        $priod_to_secession = $this->getDataThreshold()['priod_to_secession'] ?? 0;
        $col = [
            'oc_customer.customer_id',
            'oc_customer.lastname',
            'oc_customer.firstname',
            'oc_customer.buy_total',
            'oc_customer.buy_times',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_customer.customer_rank',
            'oc_customer.life_time_value',
            'oc_customer.sex',
            'oc_customer.birthday',
            'oc_customer.status',
            'oc_customer.telephone',
            'oc_customer.email',
            'oc_customer.newsletter',

            'oc_address.postcode',
            'oc_address.city',
            'oc_address.address_1',
            'oc_address.address_2',
        ];
        $data = $this->select($col)
            ->join('oc_address', 'oc_customer.address_id', '=', 'oc_address.address_id')
            ->where(function ($query) use ($param, $priod_to_secession) {
                if (!empty($param['searchText'])) {
                    $query->where(function ($query) use ($param) {
                        $query->where('oc_customer.customer_id', 'like', '%' . $param['searchText'] . '%')
                            ->orWhere('oc_customer.lastname', 'like', '%' . $param['searchText'] . '%')
                            ->orWhere('oc_customer.firstname', 'like', '%' . $param['searchText'] . '%')
                            ->orWhere('oc_customer.telephone', 'like', '%' . $param['searchText'] . '%')
                            ->orWhere('oc_customer.email', 'like', '%' . $param['searchText'] . '%');
                    });
                }

                if (!empty($param['newsLetter'])) {
                    $query->whereIn('oc_customer.newsletter', $param['newsLetter']);
                }

                if (!empty($param['customerRank'])) {
                    $query->whereIn('oc_customer.customer_rank', $param['customerRank']);
                }

                if (isset($param['ltvFrom']) && isset($param['ltvTo']) && $param['ltvFrom'] > $param['ltvTo']) {
                    $query->where('oc_customer.life_time_value', '>=', $param['ltvTo']);
                    $query->where('oc_customer.life_time_value', '<=', $param['ltvFrom']);
                } else {
                    if (isset($param['ltvFrom'])) {
                        $query->where('oc_customer.life_time_value', '>=', $param['ltvFrom']);
                    }
                    if (isset($param['ltvTo'])) {
                        $query->where('oc_customer.life_time_value', '<=', $param['ltvTo']);
                    }
                }

                if (isset($param['uriFrom']) && isset($param['uriTo']) && $param['uriFrom'] > $param['uriTo']) {
                    $query->where('oc_customer.buy_total', '>=', $param['uriTo']);
                    $query->where('oc_customer.buy_total', '<=', $param['uriFrom']);
                } else {
                    if (isset($param['uriFrom'])) {
                        $query->where('oc_customer.buy_total', '>=', $param['uriFrom']);
                    }
                    if (isset($param['uriTo'])) {
                        $query->where('oc_customer.buy_total', '<=', $param['uriTo']);
                    }
                }

                if (isset($param['buyTimesFrom']) && isset($param['buyTimesTo']) && $param['buyTimesFrom'] > $param['buyTimesTo']) {
                    $query->where('oc_customer.buy_times', '>=', $param['buyTimesTo']);
                    $query->where('oc_customer.buy_times', '<=', $param['buyTimesFrom']);
                } else {
                    if (isset($param['buyTimesFrom'])) {
                        $query->where('oc_customer.buy_times', '>=', $param['buyTimesFrom']);
                    }
                    if (isset($param['buyTimesTo'])) {
                        $query->where('oc_customer.buy_times', '<=', $param['buyTimesTo']);
                    }
                }

                if (isset($param['agesFrom']) && isset($param['agesTo']) && $param['agesFrom'] > $param['agesTo']) {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, oc_customer.birthday, CURDATE()) >=' . $param['agesTo']);
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, oc_customer.birthday, CURDATE()) <=' . $param['agesFrom']);
                } else {
                    if (isset($param['agesFrom'])) {
                        $query->whereRaw('TIMESTAMPDIFF(YEAR, oc_customer.birthday, CURDATE()) >=' . $param['agesFrom']);
                    }
                    if (isset($param['agesTo'])) {
                        $query->whereRaw('TIMESTAMPDIFF(YEAR, oc_customer.birthday, CURDATE()) <=' . $param['agesTo']);
                    }
                }

                if (!empty($param['sex'])) {
                    $query->where(function ($query) use ($param) {
                        $query->whereIn('oc_customer.sex', $param['sex']);
                        for ($index = 0; $index < count($param['sex']); $index++) {
                            if ($param['sex'][$index] == '') {
                                $query->orWhereNull('oc_customer.sex');
                                break;
                            }
                        }
                    });
                }

                if (!empty($param['prefecture'])) {
                    $query->whereIn('oc_address.city', $param['prefecture']);
                }

                if (isset($param['priod_to_secession']) && $param['priod_to_secession'] != '') {
                    //condition with rank 1, 2, 3, 4, 5
                    if ($param['priod_to_secession'] == 0) {
                        $query->whereRaw('DATEDIFF(now(), last_buy_date) <' . $priod_to_secession);
                    }
                    //condition with rank 6, 7, 8, 9, 10
                    if ($param['priod_to_secession'] != 0) {
                        $query->whereRaw('DATEDIFF(now(), last_buy_date) >=' . $priod_to_secession);
                    }
                }
            });
        if (!empty($param['sort']) && count($param['sort']) == 2) {
            $data->orderBy($param['sort'][0], $param['sort'][1]);
        } else {
            $data->orderBy('oc_customer.customer_id', 'asc');
        }
        $data = $data->paginate($limit);
        return $data;
    }

    /**
     * calculate rank for customer
     *
     * @param   [type]$date  Y-m-d
     *
     * @return  [void]
     */
    public function calculateRank($date =  null)
    {
        $date                 = $date ? $date : date('Y-m-d');
        $newToStableValue     =  $this->getDataThreshold()['new_to_stable_value'] ?? 0;
        $staExcThresholdPrice = $this->getDataThreshold()['sta_exc_threshold_price'] ?? 0;
        // reset customer_rank_old all user
        $this->dbCon->update("UPDATE oc_customer SET customer_rank_old = 0 where 1");

        // Update for customer_rank = 1
        $sqlRank1 = "
            UPDATE
                oc_customer
            SET
                customer_rank_old = customer_rank, customer_rank = 1
            WHERE
                buy_times = 1
            AND
                DATE_SUB(?, INTERVAL 240 DAY) < last_buy_date";
        $this->dbCon->update($sqlRank1, [$date]);

        // Update for customer_rank = 2
        $sqlRank2 = "UPDATE
                oc_customer
            SET
                customer_rank_old = customer_rank, customer_rank = 2
            WHERE
                buy_times = 2
            AND
                DATEDIFF(last_buy_date,first_buy_date) < ?
            AND
                DATE_SUB(?, INTERVAL 240 DAY) < last_buy_date";
        $this->dbCon->update($sqlRank2, [$newToStableValue, $date]);
        // Update for customer_rank = 3
        $sqlRank3 = "
            UPDATE
                oc_customer
            SET
                customer_rank_old = customer_rank, customer_rank = 3
            WHERE
                buy_times > 2
            AND
                DATEDIFF(last_buy_date,first_buy_date) > ?
            AND
                buy_total < ?
            AND
                DATE_SUB(?, INTERVAL 240 DAY) < last_buy_date";
        $this->dbCon->update($sqlRank3, [$newToStableValue, $staExcThresholdPrice, $date]);

        // Update for customer_rank = 4
        $sqlRank4 = "
            UPDATE
                oc_customer
            SET
                customer_rank_old = customer_rank, customer_rank = 4
            WHERE
                buy_times > 3
            AND
                DATEDIFF(last_buy_date, first_buy_date) >= 90
            AND
                DATEDIFF(last_buy_date, first_buy_date) < 210
            AND
                buy_total > ?
            AND
                DATE_SUB(?, INTERVAL 240 DAY) < last_buy_date";
        $this->dbCon->update($sqlRank4, [$staExcThresholdPrice, $date]);

        // Update for customer_rank = 5
        $sqlRank5 = "
            UPDATE
                oc_customer
            SET
                customer_rank_old = customer_rank, customer_rank = 5
            WHERE
                buy_times > 4
            AND
                DATEDIFF(last_buy_date, first_buy_date) >= 210
            AND
                buy_total > ?
            AND
                DATE_SUB(?, INTERVAL 240 DAY) < last_buy_date";
        $this->dbCon->update($sqlRank5, [$staExcThresholdPrice, $date]);

        //Caculate life_time_value
        $this->updateLifetimeValue();
    }

    /**
     * Process get infor customer
     *
     * @param int $customerID ID customer need get data
     * @return object
     */
    public function getInfoCustomer($customerID)
    {
        $col = [
            'oc_customer.customer_id',
            'oc_customer.lastname',
            'oc_customer.firstname',
            'oc_customer.lastname_kana',
            'oc_customer.firstname_kana',
            'oc_customer.sex',
            'oc_customer.birthday',
            'oc_customer.status',
            'oc_customer.telephone',
            'oc_customer.email',
            'oc_customer.fax',
            'oc_customer.newsletter',

            'oc_address.postcode',
            'oc_address.city',
            'oc_address.address_1',
            'oc_address.address_2',
        ];
        $data = $this->select($col)
            ->join('oc_address', 'oc_customer.address_id', '=', 'oc_address.address_id')
            ->where('oc_customer.customer_id', $customerID);
        return $data->first();
    }

    /**
     * Caculate all data table oc_customer
     *
     * @param   [type]$date  Y-m-d
     *
     * @return  [void]
     */
    public function calculateAgain($date = null)
    {
        $date = $date ? $date : date('Y-m-d');
        $sql = "UPDATE oc_customer c
        JOIN (
            SELECT
                SUM(o.total) AS sum_total,
                COUNT(o.order_id) AS count_order,
                MIN(o.date_added) AS mi,
                MAX(o.date_added) AS ma,
                o.customer_id
            FROM
                oc_order o
            WHERE
                o.order_status_id = 5
                AND o.store_name <> 'FUTURESHOP'
                AND o.store_name <> 'MONOTOS'
                AND DATE_FORMAT(o.date_added, '%Y-%m-%d') <= ?
            GROUP BY
                o.customer_id) p
        ON
            c.customer_id = p.customer_id
        SET
            c.buy_times = p.count_order,
            c.buy_total = p.sum_total,
            c.first_buy_date = p.mi,
            c.last_buy_date = p.ma
        ";
        $this->dbCon->update($sql, [$date]);
    }

    public function resetCustomerTracking()
    {
        $this->dbCon->update("UPDATE oc_customer SET buy_times = 0, buy_total = 0, customer_rank = 0, customer_rank_old = 0, first_buy_date = null, last_buy_date = null");
        $this->dbCon->table('mst_customer_statistics')->truncate();
    }

    public static function getDataThreshold()
    {
        if (self::$dataThreshold === null) {
            self::$dataThreshold = (new MstCustomerThreshold)->getData();
        }
        return  self::$dataThreshold;
    }

    public static function getCustomerFirstBuy($date, $schedule)
    {
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_address.postcode'
        ];
        $dataCustomer = self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->where('oc_customer.status', 1)
            ->whereDate('first_buy_date', $date);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {

            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }
        $dataCustomer = $dataCustomer->get();
        return $dataCustomer;
    }

    public static function getCustomerLastBuy($date, $schedule)
    {
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_address.postcode'
        ];

        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->where('oc_customer.status', 1)
            ->whereDate('last_buy_date', $date);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {

            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }
        $dataCustomer = $dataCustomer->get();
        return $dataCustomer;
    }


    public static function getCustomerLastBuyRecommend($date, $schedule)
    {
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_customer.product_recommend',
            'oc_address.postcode'
        ];

        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->where('oc_customer.status', 1)
            ->whereDate('last_buy_date', $date);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {

            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }
        $dataCustomer = $dataCustomer->get();
        return $dataCustomer;
    }


    public static function getCustomerBirthday($dateBirthday, $schedule)
    {
        $dateTMP = explode('-', $dateBirthday);
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_address.postcode'
        ];

        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->where('oc_customer.status', 1)
            ->whereDay('birthday', $dateTMP[1])
            ->whereMonth('birthday', $dateTMP[0]);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {

            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }

        $dataCustomer = $dataCustomer->get();
        return $dataCustomer;
    }

    /**
     * Get customer last buy
     * use for dens recommend
     */
    public static function getCustomerLastShipped($date, $schedule) {

        $arrCustomer = self::getIdCustomerShipped($date);

        if(!count($arrCustomer)) {
            return null;
        }

        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_customer.product_recommend',
            'oc_address.postcode',
        ];

        $dataCustomer =  self::select($colCustomer)
        ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
        ->where('oc_customer.status', 1)
        ->whereIn('oc_customer.customer_id', $arrCustomer);


        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {
            $receiveProperty = explode(',', $schedule['receive_property']);
            if(count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("'.implode('","', $receiveProperty).'") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }

        }

        $dataCustomer = $dataCustomer->get();

        return $dataCustomer;
    }


/**
 * Get customer send mail recommend
 * Những khách hàng đã được giao hàng 10 ngày trước đó.
 *
 *
 * @param   [type]  $date      [$date description]
 * @param   [type]  $schedule  [$schedule description]
 *
 * @return  [type]             [return description]
 */
    public static function getCustomerSendRecommend($date, $schedule) {
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_order.delivery_date',
            'oc_address.postcode',
            'oc_order.order_id'
        ];

        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->join('oc_order', 'oc_order.customer_id', 'oc_customer.customer_id')
            ->whereDate('oc_order.delivery_date', $date)
            ->whereIn('oc_order.order_status_id', ['5']);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {

            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }

        $dataCustomer = $dataCustomer->get();

        return $dataCustomer;
    }

    /**
     * Process get data customer ranking
     * @param array $schedule codition get data
     * @return object
     */
    public static function getCustomerRanking($schedule)
    {
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_address.postcode',
        ];

        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->where('oc_customer.status', 1);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {
            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }

        }
        $dataCustomer = $dataCustomer->get();

        return $dataCustomer;
    }


    public static function getCustomerType2($schedule)
    {
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_address.postcode'
        ];
        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->where('oc_customer.status', 1);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {
            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }

        $dataCustomer = $dataCustomer->get();
        return $dataCustomer;
    }

    /**
     * Get info customer recommend send mail
     *
     * @param [string]  $date date get recommend
     * @param [array]   $schedule array info schedule
     * @return collection
     */
    public static function getCustomerRecommend($date, $schedule)
    {
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_order.delivery_date',
            'oc_address.postcode',
            'oc_order.order_id'
        ];

        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->join('oc_order', 'oc_order.customer_id', 'oc_customer.customer_id')
            ->whereDate('oc_order.delivery_date', $date)
            ->whereIn('oc_order.order_status_id', ['5']);

        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {
            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }
        return $dataCustomer;
    }

    /**
     * Get info customer send mail form array id
     * User for cart, lastview, wishlist
     *
     * @param   [array]  $arrId
     * @return  [collection]
     */
    public static function getCustomerCart(array $arrId, $schedule)
    {
        if (empty($arrId)) {
            return [];
        }
        $colCustomer = [
            'oc_customer.customer_id',
            'oc_customer.firstname',
            'oc_customer.lastname',
            'oc_customer.email',
            'oc_customer.firstname_kana',
            'oc_customer.lastname_kana',
            'oc_customer.birthday',
            'oc_customer.sex',
            'oc_customer.fax',
            'oc_customer.telephone',
            'oc_customer.first_buy_date',
            'oc_customer.last_buy_date',
            'oc_address.postcode'
        ];

        $dataCustomer =  self::select($colCustomer)
            ->join('oc_address', 'oc_address.address_id', 'oc_customer.address_id')
            ->where('oc_customer.status', 1);
        $dataCustomer = $dataCustomer->whereIn('oc_customer.customer_id', $arrId);


        if (isset($schedule['receive_property']) && $schedule['receive_property'] != '') {
            $receiveProperty = explode(',', $schedule['receive_property']);
            if (count($receiveProperty) >= 1) {
                $dataCustomer = $dataCustomer->whereRaw('oc_customer.newsletter in ("' . implode('","', $receiveProperty) . '") ');
            }
        }

        if (isset($schedule['customer_target']) && $schedule['customer_target'] != '') {
            $customerTarget = explode(',', $schedule['customer_target']);
            $finalSql = self::processQueryCutomerTarget($customerTarget);
            if($finalSql) {
                $dataCustomer = $dataCustomer->whereRaw('('.$finalSql.')');
            }
        }

        $dataCustomer = $dataCustomer->get()->toArray();
        return $dataCustomer;
    }

    /**
     * Get data calculate new revenue
     *
     * @return  [collection]
     */
    public function getDataNewRevenue()
    {
        $result = $this->select(DB::raw('SUM(oc_order.total) as total_new_revenue'))
            ->join('oc_order', 'oc_order.customer_id', '=', 'oc_customer.customer_id')
            ->where('oc_customer.buy_times', 1)
            ->where('oc_order.order_status_id', 5)
            ->where('oc_order.store_name', 'DIY FACTORY ONLINE SHOP')
            ->whereMonth('oc_order.delivery_date', date('m'))
            ->whereYear('oc_order.delivery_date', date('Y'))
            ->first();
        return $result;
    }

    /**
     * Get data calculate repeat revenue
     *
     * @return  [collection]
     */
    public function getDataRepeatRevenue()
    {
        $result = $this->select(DB::raw('SUM(oc_order.total) as total_repeat_revenue'))
            ->join('oc_order', 'oc_order.customer_id', '=', 'oc_customer.customer_id')
            ->where('oc_customer.buy_times', '>', 1)
            ->where('oc_order.order_status_id', 5)
            ->where('oc_order.store_name', 'DIY FACTORY ONLINE SHOP')
            ->whereMonth('oc_order.delivery_date', date('m'))
            ->whereYear('oc_order.delivery_date', date('Y'))
            ->first();
        return $result;
    }


    /**
     * Get data calculate new revenue all
     *
     * @return  [collection]
     */
    public function getDataNewRevenueAll()
    {
        $result = $this->select(DB::raw('SUM(oc_order.total) as total_new_revenue'), 'oc_order.delivery_date')
            ->join('oc_order', 'oc_order.customer_id', '=', 'oc_customer.customer_id')
            ->where('oc_customer.buy_times', 1)
            ->where('oc_order.order_status_id', 5)
            ->where('oc_order.store_name', 'DIY FACTORY ONLINE SHOP')
            ->groupBy(DB::raw('YEAR(oc_order.delivery_date)'))
            ->groupBy(DB::raw('MONTH(oc_order.delivery_date)'))
            ->get();
        return $result;
    }

    /**
     * Get data calculate repeat revenue all
     *
     * @return  [collection]
     */
    public function getDataRepeatRevenueAll()
    {
        $result = $this->select(DB::raw('SUM(oc_order.total) as total_repeat_revenue'), 'oc_order.delivery_date')
            ->join('oc_order', 'oc_order.customer_id', '=', 'oc_customer.customer_id')
            ->where('oc_customer.buy_times', '>', 1)
            ->where('oc_order.order_status_id', 5)
            ->where('oc_order.store_name', 'DIY FACTORY ONLINE SHOP')
            ->groupBy(DB::raw('YEAR(oc_order.delivery_date)'))
            ->groupBy(DB::raw('MONTH(oc_order.delivery_date)'))
            ->get();
        return $result;
    }

    /**
     * uPDATE life_time_value
     *
     * @return  [type]  [return description]
     */
    public function updateLifetimeValue()
    {
        $sql = "
            UPDATE
                oc_customer c
            SET
                c.life_time_value  = (c.buy_total * 0.05 *(TIMESTAMPDIFF(MONTH, c.first_buy_date, c.last_buy_date) + 1))
            WHERE
                c.last_buy_date IS NOT NULL";
        $this->dbCon->update($sql);
    }

    public function calculateLTV($date =  null)
    {
        $date = $date ? $date : date('Y-m-d');
        $monthNow = Carbon::createFromFormat('Y-m-d', $date)->format('Ym');
        $monthSub12 = Carbon::createFromFormat('Ym', $monthNow)->sub('12', 'month')->format('Ym');
        $monthSub1 = Carbon::createFromFormat('Ym', $monthNow)->sub('1', 'month')->format('Ym');

        $dataCustomer = $this->selectRaw('customer_rank, CEIL(AVG(buy_total / buy_times)) as avg_total, CEIL(AVG(buy_times)) as avg_times')
            ->whereRaw('DATE_FORMAT(last_buy_date, "%Y%m")  <=  "' . $monthNow . '"')
            ->groupBy('customer_rank')
            ->get()
            ->toArray();

        $tableStatisc = new MstCustomerStatistics;

        foreach ($dataCustomer as $key => $dataRow) {
            $dataRowStatiscs = $tableStatisc->where('monthly', $monthNow)
                ->where('rank_id', $dataRow['customer_rank'])
                ->first();
            if ($dataRowStatiscs) {
                //one_month_ltv
                $oneMonthLtv = $dataRow['avg_total'] * 0.05 * $dataRow['avg_times'] * $dataRowStatiscs->mean_stay_priod;

                //one_year_ltv
                $dataStatiscs = $tableStatisc->selectRaw('SUM(one_month_ltv) as sum_month')
                    ->whereRaw('monthly <=  "' . $monthNow . '" AND monthly > "' . $monthSub12 . '"')
                    ->where('rank_id', $dataRow['customer_rank'])
                    ->first();
                $oneYearLtv = $dataStatiscs->sum_month + $oneMonthLtv;

                //cum_mean_price
                $cum_mean_price = $dataRow['avg_total'];
                $dataCheckCumMeanPrice = $tableStatisc->where('monthly', $monthSub1)
                ->where('rank_id', $dataRow['customer_rank'])
                ->first();
                if($dataCheckCumMeanPrice) {
                    $cum_mean_price +=$dataCheckCumMeanPrice->cum_mean_price;
                }

                //Update data
                $tableStatisc->where('monthly', $monthNow)
                    ->where('rank_id', $dataRow['customer_rank'])
                    ->update(
                        [
                            'one_year_ltv'   => $oneYearLtv,
                            'one_month_ltv'  => $oneMonthLtv,
                            'cum_mean_price' => $cum_mean_price,
                            'up_ope_cd'      => 1
                        ]
                    );
            }
        }
    }

    /**
     * Get list order shipped
     *
     * @param   [date]  $date  Y-m-d
     *
     * @return  [type]         [return description]
     */
    public static function getIdCustomerShipped($date) {
        return OcOrder::whereDate('delivery_date', $date)
        ->where('order_status_id', 5)
        ->pluck('customer_id')
        ->toArray();
    }

    /**
     * Process query customer target
     *
     * @param   [arary]  $customerTarget  [$customerTarget description]
     *
     * @return  [type]                   [return description]
     */
    public static function processQueryCutomerTarget(array $customerTarget) {
        $finalSql = null;
        $getDataThreshold = (new MstCustomerThreshold)->getData();
        $priod_to_secession = $getDataThreshold['priod_to_secession'] ?? 0;
        $dateCompare = Carbon::now()->sub($priod_to_secession, 'day')->format('Y-m-d');
        $sql = [];
        if(in_array(1, $customerTarget)) {
            $sql[] = 'oc_customer.customer_rank = 1';
        } elseif (in_array(6, $customerTarget)) {
            $sql[] = '(oc_customer.customer_rank = 1 AND oc_customer.last_buy_date >= "'.$dateCompare.'")';
        }
        if(in_array(2, $customerTarget)) {
            $sql[] = 'oc_customer.customer_rank = 2';
        } elseif(in_array(7, $customerTarget)) {
            $sql[] = '(oc_customer.customer_rank = 2 AND oc_customer.last_buy_date >= "'.$dateCompare.'")';
        }
        if(in_array(3, $customerTarget)) {
            $sql[] = 'oc_customer.customer_rank = 3';
        } elseif(in_array(8, $customerTarget)) {
            $sql[] = '(oc_customer.customer_rank = 3 AND oc_customer.last_buy_date >= "'.$dateCompare.'")';
        }
        if(in_array(4, $customerTarget)) {
            $sql[] = 'oc_customer.customer_rank = 4';
        } elseif(in_array(9, $customerTarget)) {
            $sql[] = '(oc_customer.customer_rank = 4 AND oc_customer.last_buy_date >= "'.$dateCompare.'")';
        }
        if(in_array(5, $customerTarget)) {
            $sql[] = 'oc_customer.customer_rank = 5';
        } elseif(in_array(10, $customerTarget)) {
            $sql[] = '(oc_customer.customer_rank = 5 AND oc_customer.last_buy_date >= "'.$dateCompare.'")';
        }
        if(in_array(0, $customerTarget)) {
            $sql[] = 'oc_customer.customer_rank = 0';
        }
        if(count($sql)) {
            $finalSql = implode(' OR ', $sql);
        }
        return $finalSql;
    }

}
