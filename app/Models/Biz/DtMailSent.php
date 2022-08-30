<?php

/**
 * Model for dt_mail_sent table.
 *
 * @package    App\Models\Biz
 * @subpackage DtMailSent
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Naruto <naruto_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class DtMailSent extends Model
{
    protected $primaryKey = 'index';
    protected $connection = 'biz';
    protected $table      = 'dt_mail_sent';
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
     * Email opened
     *
     * @param [type] $token
     * @return boolean
     */
    public function isOpen($token) {
        if($token) {
            return $this->where('token', $token)->update(['is_open' => 1]);
        }
    }

    /**
     * Email clicked
     *
     * @param [type] $token
     * @return boolean
     */
    public function isClicked($token) {
        if($token) {
            return $this->where('token', $token)->update(['is_clicked' => 1]);
        }
    }

    /**
     * Get mail send auto
     *
     * @param int $step
     * @param int $limit
     * @return object
     */
    public function getDataSendMailAuto($step, $limit)
    {
        $col = [
            'dt_mail_sent.index',
            'dt_mail_sent.customer_name',
            'dt_mail_sent_content.mail_content',
            'dt_mail_sent.mail_subject',
            'dt_mail_sent.mail_from',
            'dt_mail_sent.mail_from_name',
            'dt_mail_sent.mail_to',
            'dt_mail_sent.mail_cc',
            'dt_mail_sent.mail_bc',
        ];
        return $this->select($col)
                    ->leftjoin('dt_mail_sent_content', 'dt_mail_sent_content.mail_index', 'dt_mail_sent.index')
                    ->where('dt_mail_sent.send_status', 0)
                    ->whereRaw('dt_mail_sent.send_timing <= NOW()')
                    ->offset($step * $limit)->limit($limit)->get();
    }

    /**
     * Get data unsubemail by token
     *
     * @param int $token
     * @return object
     */
    public function getDataUnsubemail($token = '')
    {
        $result = $this->select('*')
            ->leftJoin('oc_customer', 'dt_mail_sent.customer_id', '=', 'oc_customer.customer_id')
            ->where('dt_mail_sent.token', $token)
            ->first();

        return $result;
    }

    /**
     * Get data check review product
     *
     * @param int $token
     * @return object
     */
    public function getDataCheckReview($token = '')
    {
        return $this->where('token', $token)
                ->where('is_review', 0)
                ->first();
    }
}
