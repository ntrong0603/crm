<?php

/**
 * Model for oc_order_product table.
 *
 * @package    App\Models\Biz
 * @subpackage OcOrderProduct
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;

class OcOrderProduct extends Model
{
    protected $primaryKey = 'order_product_id';
    protected $connection = 'biz';
    protected $table      = 'oc_order_product';
    protected $guarded    = [];
    public $timestamps    = false;

    /**
     * Function get product order for mail lastview
     *
     * @param   [type]  $cId   [$cId description]
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getDataLastShipped($cId, $date)
    {
        $dataProducts = [];

        $orders = OcOrder::whereDate('delivery_date', $date)
        ->where('order_status_id', 5)
        ->where('customer_id', $cId)
        ->pluck('order_id')
        ->toArray();

        if(count($orders)) {
            $colOrderProduct = [
                'oc_order_product.name',
                'oc_order_product.model',
                'oc_order_product.price',
                'oc_product.rated',
                'oc_product.rated_time',
                'oc_product.image',
                'oc_product.product_id',
            ];
            $dataProducts = $this->select($colOrderProduct)
                ->join('oc_product', 'oc_product.product_id', 'oc_order_product.product_id')
                ->whereIn('oc_order_product.order_id', $orders)
                ->whereNotNull('oc_product.image')
                ->where('oc_product.image', "<>", "")
                ->get()
                ->keyBy('model')
                ->toArray();
        }
        return $dataProducts;
    }


    public function getProductRecommend($cId, $arrProductExist, $date)
    {
        $orders = OcOrder::whereDate('delivery_date', $date)
        ->where('order_status_id', 5)
        ->where('customer_id', $cId)
        ->pluck('order_id')
        ->toArray();

        $cols = [
            'oc_product.product_id',
            'oc_product.model',
            'oc_product.image',
            'oc_product.rated',
            'oc_product.price',
            'oc_product.rated_time',
            'oc_product_info.name',
        ];
        $dataRecommend = [];
        $arrProductBuy =  (new OcOrderProduct)->whereIn('order_id', $orders)->pluck('model')->toArray();
        if($arrProductBuy) {
            $dataRecommend = (new Recommend)->select($cols)
                ->distinct()
                ->join('oc_product', 'oc_product.model', 'recommend.ko_code')
                ->join('oc_product_info', 'oc_product_info.product_id', '=', 'oc_product.product_id')
                ->where('oc_product.status', 1)
                ->where('recommend.order', 2)
                ->whereIn('oya_code', $arrProductBuy)
                ->where('recommend.order', 2)
                ->whereNotNull('oc_product.image')
                ->where('oc_product.image', "<>", "")
                ->whereRaw('(oc_product.delivery_flg  NOT IN ("h", "k", "ka", "kt") OR  oc_product.delivery_flg IS NULL)');
                if(is_array($arrProductExist) && count($arrProductExist)) {
                    $dataRecommend = $dataRecommend->whereNotIn('ko_code', $arrProductExist);
                }
                $dataRecommend = $dataRecommend->orderBy('recommend.priority', 'asc')
                ->limit(6)
                ->get()
                ->keyBy('model')
                ->toArray();
        }
        return $dataRecommend;
    }

}
