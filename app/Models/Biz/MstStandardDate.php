<?php

/**
 * Model for mst_standard_date table.
 *
 * @package    App\Models\Biz
 * @subpackage MstStandardDate
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class MstStandardDate extends Model
{
    protected $primaryKey = 'standard_id';
    protected $connection = 'biz';
    protected $table      = 'mst_standard_date';
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
     * Get name standard date
     *
     * @return void
     */
    public function getName() {
        return $this->pluck('standard_date_name', 'standard_id')->all();
    }
}
