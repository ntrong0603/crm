<?php

/**
 * Model for log_query_process_batches table.
 *
 * @package    App\Models\Biz
 * @subpackage LogQueryProcessBatches
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Truong Nghia<shikamaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class LogQueryProcessBatches extends Model
{
    public $timestamps = false;

    /**
    * The database table used by the model.
    * @var string
    */
    protected $table = 'log_query_process_batches';

    /**
    * The database is used by the model.
    * @var string
    */
    protected $connection = 'biz_log';
}