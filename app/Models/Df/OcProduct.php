<?php

/**
 * Model for oc_product table.
 *
 * @package    App\Models\Df
 * @subpackage OcProduct
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Arr;

class OcProduct extends Model
{
    protected $primaryKey = 'product_id';
    protected $connection = 'df';
    protected $table      = 'oc_product';
    const CREATED_AT      = 'date_added';
    const UPDATED_AT      = 'date_modified';
    protected $guarded    = [];

    /**
     * Process update info mail setting
     *
     * @param  array $where condition
     * @param  array $data update
     * @return boolean
     */
    public function updateData($where, $data)
    {
        $result = $this->where($where)
            ->update($data);
        return $result;
    }

    /**
     * Process get list product
     *
     * @param array $param condition get data
     * @param int $limit limit record need get
     * @return object
     */
    public function getDatas($param = '', $limit = 25)
    {
        $col = [
            'oc_product.product_id',
            'oc_product.model',
            'oc_product.jan',
            'oc_product.price',
            'oc_product.name',
        ];
        $data = $this->select($col)
            ->where(function ($query) use ($param) {
                if (!empty($param['key_search'])) {
                    $query->where(function ($query) use ($param) {
                        if (!empty($param['product_name'])) {
                            $query->where('oc_product.name', 'like', '%' . $param['key_search'] . '%');
                        }
                        if (!empty($param['product_code'])) {
                            $query->orWhere('oc_product.model', 'like', '%' . $param['key_search'] . '%');
                        }
                        if (!empty($param['product_jan'])) {
                            $query->orWhere('oc_product.jan', 'like', '%' . $param['key_search'] . '%');
                        }
                    });
                }
                if (!empty($param['price_from'])) {
                    $query->where("oc_product.price", ">=", $param['price_from']);
                }
                if (!empty($param['price_to'])) {
                    $query->where("oc_product.price", "<=", $param['price_to']);
                }
                if (!empty($param['not_get_model'])) {
                    $query->whereNotIn("oc_product.model", $param['not_get_model']);
                }
            });
        $data->orderBy('oc_product.product_id', 'asc');
        $data = $data->paginate($limit);
        return $data;
    }

    /**
     * Process get data product
     *
     * @param array $whereArr condition get info product
     * @return object
     */
    public function getDataEditMailSetting($whereArr = [])
    {
        if (empty($whereArr)) {
            return false;
        }
        $col = [
            'oc_product.model',
            'oc_product.name',
        ];
        $data = $this->select($col)
            ->where($whereArr)
            ->first();
        return $data;
    }

    /**
     * Get product together from recommend_amazon
     *
     * @param   [type]  $model  [$model description]
     * @param   [type]  $type   [$type description]
     * @param   [type]  $limit  [$limit description]
     *
     * @return  [type]          [return description]
     */
    public function getProductTogether($model, $type = 1, $limit = 1)
    {
        $cols = [
            'oc_product.product_id',
            'oc_product.model',
            'oc_product.image',
            'oc_product.rated',
            'oc_product.price',
            'oc_product.rated_time',
            'oc_product.name',
        ];
        $data = $this->select($cols)
            ->join('recommend_amazon', 'recommend_amazon.ko_code', '=', 'oc_product.model')
            ->where('recommend_amazon.oya_code', $model)
            ->where('oc_product.status', 1)
            ->where('recommend_amazon.type', $type)
            ->whereNotNull('oc_product.image')
            ->where('oc_product.image', "<>", "")
            ->whereRaw('(oc_product.delivery_flg  NOT IN ("h", "k", "ka", "kt") OR  oc_product.delivery_flg IS NULL)')
            ->orderBy('recommend_amazon.priority', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * Procee get data product ranking
     *
     * @param array $param array condition get data
     * @return object
     */
    public function getProductRanking($param)
    {
        $col = [
            'oc_product.name',
            'oc_product.image',
            'oc_product.model',
            'oc_product.price',
            'oc_product.rated',
            'oc_product.rated_time',
        ];
        $data = $this->select($col)
            ->leftJoin('category_product_list_honten', 'category_product_list_honten.product_code', 'oc_product.model')
            ->whereNotNull('oc_product.image')
            ->where('oc_product.image', "<>", "");
        if (!empty($param['category_lar_code'])) {
            $data = $data->where('category_product_list_honten.category_lar_code', $param['category_lar_code']);
        }
        $data = $data->where('oc_product.status', 1)
            ->where(function ($query) {
                $query->whereNotIn('oc_product.delivery_flg', ['h', 'k', 'ka', 'kt'])
                    ->orWhereNull('oc_product.delivery_flg');
            });
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
        if (!empty($param['limit'])) {
            $data = $data->limit($param['limit']);
        }

        return $data->get();
    }
}
