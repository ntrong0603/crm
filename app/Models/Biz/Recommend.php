<?php

/**
 * Model for oc_order table.
 *
 * @package    App\Models\Biz
 * @subpackage Recommend
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
    protected $connection = 'biz';
    protected $table      = 'recommend';
    const CREATED_AT      = 'register_date';
    const UPDATED_AT      = 'up_date';
}
