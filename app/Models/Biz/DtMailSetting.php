<?php

/**
 * Model for dt_mail_setting table.
 *
 * @package    App\Models\Biz
 * @subpackage DtMailSetting
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Biz\DtMailSchedule;

class DtMailSetting extends Model
{
    protected $primaryKey = 'mail_setting_id';
    protected $connection = 'biz';
    protected $table      = 'dt_mail_setting';
    const CREATED_AT      = 'in_date';
    const UPDATED_AT      = 'up_date';
    protected $guarded    = [];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'in_date' => 'datetime:Y-m-d H:i:s',
        'up_date' => 'datetime:Y-m-d H:i:s',
    ];

    public function schedules()
    {
        return $this->hasMany(DtMailSchedule::class, 'mail_setting_id', 'schedule_id');
    }

    /**
     * Process update info mail setting
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
     * Process get list mail setting
     *
     * @param array $param condition get data
     * @param int $limit limit record need get
     * @return object
     */
    public function getDatas($param = [], $limit = 25)
    {
        $col = [
            'dt_mail_setting.mail_setting_id',
            'dt_mail_setting.setting_status',
            'dt_mail_setting.setting_name',
            'mst_standard_date.standard_date_name',
            'dt_mail_setting.up_date',
            'dt_mail_setting.standard_id'
        ];
        $data = $this->select($col)
            ->selectRaw('COUNT(dt_mail_schedule.mail_setting_id) as total')
            ->leftJoin('dt_mail_schedule', 'dt_mail_setting.mail_setting_id', '=', 'dt_mail_schedule.mail_setting_id')
            ->leftJoin('mst_standard_date', 'dt_mail_setting.standard_id', '=', 'mst_standard_date.standard_id')
            ->where(function ($query) use ($param) {
                if (!empty($param['search_keyword'])) {
                    $query->where(function ($query) use ($param) {
                        $query->where('dt_mail_setting.setting_name', 'like', '%' . $param['search_keyword'] . '%')
                            ->orWhere('dt_mail_setting.remarks', 'like', '%' . $param['search_keyword'] . '%');
                    });
                }
                if (!empty($param['search_create_date_from'])) {
                    $query->whereDate('dt_mail_setting.in_date', '>=', $param['search_create_date_from']);
                }
                if (!empty($param['search_create_date_to'])) {
                    $query->whereDate('dt_mail_setting.in_date', '<=', $param['search_create_date_to']);
                }
                if (!empty($param['mail_type'])) {
                    $query->where('dt_mail_setting.mail_type', $param['mail_type']);
                }
                //status mail setting
                if (isset($param['filter'])) {
                    $query->where('dt_mail_setting.setting_status', $param['filter']);
                }
            });
        if (config('app.env') === 'production') {
            $data = $data->where('dt_mail_setting.standard_id', "<>", 5); // Ẩn các schedule cart
        }
        $data->groupBy(
            'dt_mail_setting.mail_setting_id',
            'dt_mail_setting.setting_status',
            'dt_mail_setting.setting_name',
            'mst_standard_date.standard_date_name',
            'dt_mail_setting.up_date',
            'dt_mail_setting.standard_id'
        );

        if (!empty($param['sort']) && count($param['sort']) >= 2 && count($param['sort']) <= 3 && in_array($param['sort'][1], ['asc', 'desc'])) {
            if (!empty($param['sort'][2])) {
                $data->orderBy($param['sort'][2] . "." . $param['sort'][0], $param['sort'][1]);
            } else {
                $data->orderBy($param['sort'][0], $param['sort'][1]);
            }
        }

        $data->orderBy('dt_mail_setting.mail_setting_id', 'asc');
        $data = $data->paginate($limit);
        return $data;
    }

    /**
     * Process get list mail schedule
     *
     * @param array $param condition get data
     * @param array $arrSort sort
     * @return object
     */
    public function getDataSchedule($param = [], $arrSort = [], $limit = 10)
    {
        $col = [
            'dt_mail_setting.setting_name',
            'dt_mail_schedule.schedule_name',
            'dt_mail_setting.mail_type',
            'dt_mail_setting.setting_status',
            'dt_mail_setting.up_date',
        ];
        $data = $this->select($col)
            ->join('dt_mail_schedule', 'dt_mail_setting.mail_setting_id', '=', 'dt_mail_schedule.mail_setting_id')
            ->where(function ($query) use ($param) {
                if (!empty($param['search_keyword'])) {
                    $query->where('dt_mail_setting.setting_name', 'like', '%' . $param['search_keyword'] . '%')
                        ->orWhere('dt_mail_setting.remarks', 'like', '%' . $param['search_keyword'] . '%');
                }
                if (!empty($param['search_create_date_from'])) {
                    $query->where('dt_mail_setting.in_date', '>=', $param['search_create_date_from']);
                }
                if (!empty($param['search_create_date_to'])) {
                    $query->where('dt_mail_setting.in_date', '<=', $param['search_create_date_to']);
                }
            });
        // $data->groupBy('dt_mail_sent.mail_setting_id');
        $data->orderBy('dt_mail_setting.mail_setting_id', 'asc');
        $data = $data->paginate($limit);
        return $data;
    }

    /**
     * Process get data mail setting
     *
     * @param array $whereData condition get data
     * @return object
     */
    public function getData($whereData = [])
    {
        if (empty($whereData)) {
            return false;
        }

        $col = [
            'mail_setting_id',
            'setting_name',
            'remarks',
            'setting_status',
            'receive_property',
            'customer_target',
            'ltv_from',
            'ltv_to',
            'purchased_date_from',
            'purchased_date_to',
            'first_time_purchased_date_from',
            'first_time_purchased_date_to',
            'last_time_purchased_date_from',
            'last_time_purchased_date_to',
            'purchased_times_from',
            'purchased_times_to',
            'cumulative_of_earnings_from',
            'cumulative_of_earnings_to',
            'old_from',
            'old_to',
            'standard_id',
            'sex',
            'prefectures',
            'is_all_or_one_purchase',
            'is_buyed',
            'is_all_or_one_stop',
            'product_specify',
            'mail_type',
        ];
        $data = $this->select($col)->where($whereData)->first();
        return $data;
    }

    /**
     * Process update or insert condition not isset
     *
     * @param array $id array id need update or create
     * @param array $dataUpdate array data need update
     * @return void
     */
    public function insertOrUpdate($id, $dataUpdate)
    {
        return $this->updateOrCreate($id, $dataUpdate);
    }
    /**
     * Process get list mail setting
     *
     * @param array $param condition get data
     * @param int $limit limit record need get
     * @return object
     */
    public function getDataListMail($param = [], $arraySort = [], $limit = 25)
    {
        $cols = [
            'dt_mail_sent.mail_subject',
            'dt_mail_setting.mail_type',
            DB::raw("MAX(dt_mail_sent.up_date) as sent_date"),
            DB::raw("COUNT(*) as total_sent"),
            DB::raw("SUM(is_open) as open_num"),
            DB::raw("(SUM(is_open)/COUNT(*))*100 as open_percent"),
            DB::raw("SUM(is_clicked) as clicked_num"),
            DB::raw("(SUM(is_clicked)/COUNT(*))*100 as clicked_percent"),
            DB::raw("SUM(is_send_error) as send_error_num")
        ];
        $data = $this->select($cols)
            ->join('dt_mail_sent', 'dt_mail_setting.mail_setting_id', '=', 'dt_mail_sent.mail_setting_id')
            ->where(function ($query) use ($param) {
                if (!empty($param['searchText'])) {
                    $query->where('dt_mail_sent.mail_subject', "like", "%{$param['searchText']}%");
                    // $query->where('dt_mail_setting.setting_name', 'like', "%{$param['searchText']}%"
                    //     ->orWhere('dt_mail_setting.remarks', 'like', "%{$param['searchText']}%";
                }
                if (!empty($param['search_last_date_from'])) {
                    $query->whereDate('dt_mail_sent.up_date', '>=', trim($param['search_last_date_from']));
                }
                if (!empty($param['search_last_date_to'])) {
                    $query->whereDate('dt_mail_sent.up_date', '<=', trim($param['search_last_date_to']));
                }
                if (!empty($param['search_last_time_from'])) {
                    $query->whereTime('dt_mail_sent.up_date', '>=', trim($param['search_last_time_from']));
                }
                if (!empty($param['search_last_time_to'])) {
                    $query->whereTime('dt_mail_sent.up_date', '<=', trim($param['search_last_time_to']));
                }
                if (!empty($param['customer_id'])) {
                    $query->where('dt_mail_sent.customer_id', 'like', "%{$param['customer_id']}%");
                }
                if (!empty($param['customer_name'])) {
                    $query->where('dt_mail_sent.customer_name', 'like', "%{$param['customer_name']}%");
                }
                if (!empty($param['customer_mail'])) {
                    $query->where('dt_mail_sent.mail_to', 'like', "%{$param['customer_mail']}%");
                }
                if (!empty($param['mail_type'])) {
                    $query->where('dt_mail_setting.mail_type', $param['mail_type']);
                }
            });

        //Sort
        // dd($arraySort);
        $check = false;
        if (count($arraySort) > 0) {
            foreach ($arraySort as $column => $sort) {
                if ($sort !== null && in_array($sort, ['asc', 'desc'])) {
                    $data->orderBy($column, $sort);
                    $check = true;
                }
            }
        }
        if (!$check) {
            $data->orderBy('dt_mail_setting.mail_setting_id', 'asc');
        }

        $data->groupBy(
            "dt_mail_setting.mail_setting_id",
            "dt_mail_setting.mail_type",
            "dt_mail_sent.mail_subject"
        );
        $data = $data->paginate($limit);

        return $data;
    }
}
