<?php

/**
 * Model for dt_mail_sent table.
 *
 * @package    App\Models\Biz
 * @subpackage DtMailSentContent
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Naruto <naruto_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class DtMailSentContent extends Model
{
    protected $primaryKey = 'mail_index';
    protected $connection = 'biz';
    protected $table      = 'dt_mail_sent_content';
    public $timestamps = false;
    protected $guarded    = [];

}
