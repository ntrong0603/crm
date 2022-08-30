<?php

/**
 * Model for mst_customer_statistics table.
 *
 * @package    App\Models\Df
 * @subpackage MstCustomerThreshold
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstCustomerThreshold extends Model
{
    protected $connection = 'df';
    protected $table      = 'mst_customer_threshold';
    const CREATED_AT      = 'in_date';
    const UPDATED_AT      = 'up_date';
    protected $guarded    = [];
    protected $dbCon = '';

    public function __construct()
    {
        $this->dbCon = DB::connection($this->connection);
    }

    /**
     * @param  array condition $where
     * @param  array update $data
     * @return boolean
     */
    public function updateData($data)
    {
        $result = $this->find(1)
            ->update($data);
        return $result;
    }

    /**
     * Get data
     *
     * @return  [type]  [return description]
     */
    public function getData() {
        $data = $this->find(1);
        return $data;
    }
}
