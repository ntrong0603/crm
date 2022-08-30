<?php

/**
 * Model for oc_order table.
 *
 * @package    App\Models\Df
 * @subpackage OcOrder
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;

class OcOrder extends Model
{
    protected $primaryKey = 'order_id';
    protected $connection = 'df';
    protected $table      = 'oc_order';
    const CREATED_AT      = 'date_added';
    const UPDATED_AT      = 'date_modified';
    protected $guarded    = [];
}
