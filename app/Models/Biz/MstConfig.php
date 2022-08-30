<?php

/**
 * Model for mst_config table.
 *
 * @package    App\Models\Biz
 * @subpackage MstConfig
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Naruto <naruto_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class MstConfig extends Model
{
    protected $table      = 'mst_config';
    protected $connection = 'biz';
    public $timestamps = false;
    protected $guarded    = [];
    
    public static $allconfig = null;

    /**
     * Get all config
     */
    public static function getAllconfig() {
        if(self::$allconfig == null) {
            self::$allconfig = self::pluck('value', 'key')->toArray();
        }
        return self::$allconfig;
    }

}
