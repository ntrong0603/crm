<?php

/**
 * Model for oc_order table.
 *
 * @package    App\Models\Biz
 * @subpackage OcOrder
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class OcOrder extends Model
{
    protected $primaryKey = 'order_id';
    protected $connection = 'biz';
    protected $table      = 'oc_order';
    const CREATED_AT      = 'date_added';
    const UPDATED_AT      = 'date_modified';
    protected $guarded    = [];
}
