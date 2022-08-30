<?php

/**
 * Model for dt_mail_schedule table.
 *
 * @package    App\Models\Biz
 * @subpackage DtMailSchedule
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Naruto <naruto_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;
use App\Models\Biz\DtMailSetting;
use App\Models\Biz\OcCustomer;

class DtMailSchedule extends Model
{
    protected $primaryKey = 'schedule_id';
    protected $connection = 'biz';
    protected $table      = 'dt_mail_schedule';
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

    public function setting()
    {
        return $this->belongsTo(DtMailSetting::class, 'mail_setting_id', 'schedule_id');
    }

    /**
     * [getSchedule description]
     *
     * @param   [int] $mailType     1 | 2
     * @param   [int]$standard_id  mst_standard_date
     * @param   null                [ description]
     *
     * @return  [type]              [return description]
     */
    public function getSchedule($mailType = 2, $standard_id = null)
    {
        $colSelect = [
            'dt_mail_schedule.mail_template_id',
            'dt_mail_schedule.mail_setting_id',
            'dt_mail_schedule.schedule_id',
            'dt_mail_schedule.minute',
            'dt_mail_schedule.hour',
            'dt_mail_schedule.date',
            'dt_mail_schedule.is_after',
            'dt_mail_schedule.date_num',
            'dt_mail_schedule.mail_from',
            'dt_mail_schedule.mail_from_name',
            'dt_mail_schedule.subject',
            'dt_mail_schedule.mail_template_option',
            'dt_mail_schedule.link_unsubscribe',
            'dt_mail_setting.customer_target',
            'dt_mail_setting.receive_property',
            'dt_mail_setting.standard_id',
            'dt_mail_setting.product_specify',
            'dt_mail_template.mail_content_html',
            'dt_mail_template.mail_content_text'
        ];
        $data =  $this->select($colSelect)
            ->join('dt_mail_setting', 'dt_mail_setting.mail_setting_id', 'dt_mail_schedule.mail_setting_id')
            ->join('dt_mail_template', 'dt_mail_template.mail_template_id', 'dt_mail_schedule.mail_template_id')
            ->where('dt_mail_schedule.schedule_status', 1)
            ->where('dt_mail_schedule.is_run', 0)
            ->where('dt_mail_setting.setting_status', 1)
            ->where('dt_mail_setting.mail_type', $mailType);
        if ($standard_id) {
            $data = $data->where('dt_mail_setting.standard_id', $standard_id);
        }
        if ($mailType == 2) {
            $data = $data->whereDate('dt_mail_schedule.date', date('Y-m-d'));
        }
        $data = $data->orderBy('dt_mail_setting.mail_setting_id')
            ->get();
        return $data;
    }

    /**
     * Reset schedule type 1
     *
     * @return  [type]  [return description]
     */
    public function resetSchedule($standard = null)
    {
        if ($standard) {
            return $this->join('dt_mail_setting', 'dt_mail_setting.mail_setting_id', 'dt_mail_schedule.mail_setting_id')
                ->where('dt_mail_setting.mail_type', 1)
                ->where('dt_mail_setting.standard_id', $standard)
                ->update([
                    'dt_mail_schedule.is_run' => 0,
                    'dt_mail_schedule.up_ope_cd' => 1
                ]);
        } else {
            return $this->join('dt_mail_setting', 'dt_mail_setting.mail_setting_id', 'dt_mail_schedule.mail_setting_id')
                ->where('dt_mail_setting.mail_type', 1)
                ->update([
                    'dt_mail_schedule.is_run' => 0,
                    'dt_mail_schedule.up_ope_cd' => 1
                ]);
        }
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
     * Process get list schedule
     *
     * @param array $param condition get data
     * @return object
     */
    public function getDatas($param = [], $limit = 25)
    {
        $col = [
            'dt_mail_setting.mail_setting_id',
            'dt_mail_setting.setting_name',
            'dt_mail_setting.setting_status',
            'dt_mail_setting.mail_type',
            'dt_mail_schedule.schedule_name',
            'dt_mail_schedule.schedule_status',
            'dt_mail_schedule.in_date',
        ];
        $data = $this->select($col)
            ->join('dt_mail_setting', 'dt_mail_setting.mail_setting_id', '=', 'dt_mail_schedule.mail_setting_id')
            ->where(function ($query) use ($param) {
                if (!empty($param['search_keyword'])) {
                    $query->where(function ($query) use ($param) {
                        $query->where('dt_mail_setting.setting_name', 'like', '%' . $param['search_keyword'] . '%')
                            ->orWhere('dt_mail_setting.remarks', 'like', '%' . $param['search_keyword'] . '%');
                    });
                }
                if (!empty($param['search_mail_type'])) {
                    $query->where('dt_mail_setting.mail_type', $param['search_mail_type']);
                }
                if (!empty($param['search_create_date_from'])) {
                    $query->whereDate('dt_mail_schedule.in_date', '>=', $param['search_create_date_from']);
                }
                if (!empty($param['search_create_date_to'])) {
                    $query->whereDate('dt_mail_schedule.in_date', '<=', $param['search_create_date_to']);
                }
            });
        if (config('app.env') === 'production') {
            $data = $data->where('dt_mail_setting.standard_id', "<>", 5); // Ẩn các schedule cart
        }
        $data = $data->paginate($limit);
        return $data;
    }

    /**
     * Process get data
     *
     * @param array $param condition get data
     * @return object
     */
    public function getData($param = [])
    {
        if (empty($param)) {
            return false;
        }
        $col = [
            'dt_mail_setting.mail_setting_id',
            'dt_mail_setting.setting_name',
            'dt_mail_setting.setting_status',
            'dt_mail_setting.mail_type',
            'dt_mail_schedule.schedule_name',
            'dt_mail_schedule.schedule_status',
            'dt_mail_schedule.schedule_id',
            'dt_mail_schedule.mail_template_id',
            'dt_mail_schedule.subject',
            'dt_mail_schedule.mail_template_option',
            'dt_mail_schedule.link_unsubscribe',
            'dt_mail_schedule.mail_from',
            'dt_mail_schedule.mail_from_name',
            'dt_mail_schedule.in_date',
        ];
        $data = $this->select($col)
            ->join('dt_mail_setting', 'dt_mail_setting.mail_setting_id', '=', 'dt_mail_schedule.mail_setting_id')
            ->where(function ($query) use ($param) {
                if (!empty($param['schedule_id'])) {
                    $query->where("schedule_id", $param['schedule_id']);
                }
            });
        $data = $data->first();
        return $data;
    }

    /**
     * Process function get data in edit maill setting
     *
     * @param array $whereArr
     * @return object
     */
    public function getDataEditMailSetting($whereArr = [])
    {
        if (empty($whereArr)) {
            return false;
        }
        $col = [
            'schedule_id',
            'schedule_name',
            'date_num',
            'date',
            'schedule_status',
            'mail_template_id',
            'is_after',
            'hour',
            'minute',
        ];
        $data = $this->select($col)->where($whereArr)->get();
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
}
