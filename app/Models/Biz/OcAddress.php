<?php

/**
 * Model for oc_address table.
 *
 * @package    App\Models\Biz
 * @subpackage OcAddress
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class OcAddress extends Model
{
    protected $primaryKey = 'address_id';
    protected $connection = 'biz';
    protected $table      = 'oc_address';
    const CREATED_AT      = 'date_added';
    const UPDATED_AT      = 'date_modified';
    protected $guarded    = [];

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
}
