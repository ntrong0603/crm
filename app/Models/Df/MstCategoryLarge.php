<?php

/**
 * Model for mst_category_large table.
 *
 * @package    App\Models\Df
 * @subpackage MstCategoryLarge
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class MstCategoryLarge extends Model
{
    protected $primaryKey = 'category_lar_code';
    protected $connection = 'df';
    protected $table      = 'mst_category_large';
    const CREATED_AT      = 'created_at';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded    = [];

    /**
     * Process get category ranking
     *
     * @param array $param condition get data
     * @return object
     */
    public function getCategoryRanking($param)
    {
        $col = [
            'category_lar_code',
            'category_lar_name',
            'order_rank',
        ];
        $data = $this->select($col);
        if (isset($param['order_rank'])) {
            $data = $data->where("order_rank", ">", $param['order_rank']);
        }
        if (!empty($param['limit'])) {
            $data = $data->limit($param['limit']);
        }
        if (!empty($param['order_by']) && is_array($param['order_by'])) {
            if (Arr::isAssoc($param['order_by'])) {
                foreach ($param['order_by'] as $orderBy) {
                    if (count($orderBy) == 2 && in_array($orderBy[1], ['asc', 'desc', 'ASC', 'DESC'])) {
                        $data = $data->orderBy($orderBy[0], $orderBy[1]);
                    }
                }
            } else {
                if (count($param['order_by']) == 2 && in_array($param['order_by'][1], ['asc', 'desc', 'ASC', 'DESC'])) {
                    $data = $data->orderBy($param['order_by'][0], $param['order_by'][1]);
                }
            }
        }
        return $data->get();
    }
}
