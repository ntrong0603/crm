<?php

/**
 * Model for oc_product_recent_view table.
 *
 * @package    App\Models\Df
 * @subpackage OcProductRecentView
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Naruto <naruto_vn@monotos.biz>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;
use DB;

class OcProductRecentView extends Model
{
    protected $connection = 'df';
    protected $table      = 'oc_product_recent_view';
    public $timestamps = false;
    protected $guarded    = [];
    protected $dbCon = '';

    public function __construct()
    {
        $this->dbCon = DB::connection($this->connection);
    }
}
