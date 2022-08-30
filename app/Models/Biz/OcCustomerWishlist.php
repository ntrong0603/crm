<?php

/**
 * Model for oc_customer_wishlist table.
 *
 * @package    App\Models\Biz
 * @subpackage OcCustomerWishlist
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Naruto <naruto_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;
use DB;

class OcCustomerWishlist extends Model
{
    protected $connection = 'biz';
    protected $table      = 'oc_customer_wishlist';
    public $timestamps = false;
    protected $guarded    = [];
    protected $dbCon = '';

    public function __construct()
    {
        $this->dbCon = DB::connection($this->connection);
    }
}
