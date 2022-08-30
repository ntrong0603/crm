<?php

/**
 * Model for dt_mail_templte table.
 *
 * @package    App\Models\Df
 * @subpackage DtMailTemplate
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;

class DtMailTemplate extends Model
{
    protected $primaryKey = 'mail_template_id';
    protected $connection = 'df';
    protected $table      = 'dt_mail_template';
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
     * Process get list mail template
     *
     * @param array $param condition get data
     * @param int $limit limit record need get
     * @return object
     */
    public function getDatas($param = [], $limit = 25)
    {
        $col = [
            'dt_mail_template.mail_template_id',
            'dt_mail_template.template_name',
            'dt_mail_template.is_protected',
        ];
        $data = $this->select($col)
            ->orderBy('dt_mail_template.mail_template_id', 'asc')
            ->paginate($limit);
        return $data;
    }

    /**
     * Process get data template
     *
     * @param array $param condition get data
     * @return object
     */
    public function getData($param = [])
    {
        if (empty($param)) {
            return false;
        }
        $data = $this->where(function ($query) use ($param) {
            if (!empty($param['idTemplate'])) {
                $query->where("mail_template_id", $param['idTemplate']);
            }
        });
        $data = $data->first();
        return $data;
    }
}
