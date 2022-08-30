<?php

/**
 * Model for oc_address table.
 *
 * @package    App\Models\Df
 * @subpackage OcAddress
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;

class OcAddress extends Model
{
    protected $primaryKey = 'address_id';
    protected $connection = 'df';
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
