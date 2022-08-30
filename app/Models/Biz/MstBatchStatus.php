<?php

/**
 * Model for mst_batch_status table.
 *
 * @package    App\Models\Biz
 * @subpackage MstStatusBatch
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Truong Nghia<shikamaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class MstBatchStatus extends Model
{
    protected $guarded    = [];
    protected $connection = 'biz';
    public $timestamps = false;


    /**
    * The database table used by the model.
    * @var string
    */
    protected $table = 'mst_batch_status';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'signature';

    /**
    * The type of primary key.
    * @var string
    */
    protected $keyType = 'string';

    /**
     * Get data by signature
     * @param  String $signature
     * @return object
     */
    public function getDataBySignature($signature)
    {
        return $this->find($signature);
    }

    /**
     * Get batch mall
     *
     * @param   [type]$mall  [$mall description]
     * @param   null         [ description]
     *
     * @return  [type]       [return description]
     */
    public function getBatch($mall = null) {
        $mall = ($mall) ? $mall : 'biz';
        return $this->where('mall', $mall)->get();
    }
}
