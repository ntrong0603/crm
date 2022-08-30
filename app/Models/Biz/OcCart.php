<?php

/**
 * Model for oc_cart table.
 *
 * @package    App\Models\Biz
 * @subpackage OcCart
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

namespace App\Models\Biz;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class OcCart extends Model
{
    protected $primaryKey = 'cart_id';
    protected $connection = 'biz';
    protected $table      = 'oc_cart';
    const CREATED_AT      = 'date_added';
    protected $dbCon = '';
    protected $guarded    = [];
    public static $dataThreshold = null;

    public function __construct()
    {
        $this->dbCon = DB::connection($this->connection);
    }

    /**
     * Get data from cart
     *
     * @param [type] $date
     * @return void
     */
    public function getDataCart()
    {
        // xử lý cart trong giỏ hàng từ cách thời điểm xet từ 1,2 tiếng
        $h1 = Carbon::now()->subHour('1')->format('Y-m-d H:i:s');
        $h2 = Carbon::now()->subHour('2')->format('Y-m-d H:i:s');

        $products = [];
        $arrCustomer =  $this
            ->where(function($query) use($h2, $h1){
                $query->where('date_added', '>', $h2)
                    ->Where('date_added', '<', $h1);
            })
            ->groupBy('customer_id')
            ->pluck('customer_id')
            ->toArray();

        if($arrCustomer) {
            $cols = [
                'oc_cart.product_id',
                'oc_product.quantity',
                'oc_product.price',
                'oc_product.model',
                'oc_product.image',
                'oc_product.rated',
                'oc_product.rated_time',
                'oc_product_info.name',
                'oc_cart.customer_id',
            ];
            $products = $this->select($cols)
                ->join('oc_product', 'oc_product.product_id', 'oc_cart.product_id')
                ->join('oc_product_info', 'oc_product_info.product_id', 'oc_cart.product_id')
                ->where(function($query) use($h2, $h1){
                    $query->where('oc_cart.date_added', '>', $h2)
                        ->where('oc_cart.date_added', '<', $h1);
                })
                ->whereNotNull('oc_product.image')
                ->where('oc_product.image', "<>", "")
                ->where('oc_product.status', 1);
                $products = $products->whereIn('oc_cart.customer_id', $arrCustomer);
                $products = $products->orderBy('oc_cart.date_added', 'desc')
                ->get()
                ->groupBy('customer_id')
                ->toArray();
        }
        return $products;
    }

    /**
     * Get data from wihslist
     *
     * @param [type] $date
     * @return void
     */
    public function getDataWishlist($date)
    {
        $products = [];
        $arrCustomer =  (new OcCustomerWishlist)
            ->whereDate('date_added', $date)
            ->groupBy('customer_id')
            ->pluck('customer_id')
            ->toArray();
        if($arrCustomer) {
            $cols = [
                'oc_customer_wishlist.product_id',
                'oc_product.quantity',
                'oc_product.price',
                'oc_product.model',
                'oc_product.image',
                'oc_product.rated',
                'oc_product.rated_time',
                'oc_product_info.name',
                'oc_customer_wishlist.customer_id',
            ];
            $products = (new OcCustomerWishlist)
                ->select($cols)
                ->join('oc_product', 'oc_product.product_id', 'oc_customer_wishlist.product_id')
                ->join('oc_product_info', 'oc_product_info.product_id', 'oc_customer_wishlist.product_id')
                ->where('oc_product.status', 1)
                ->whereNotNull('oc_product.image')
                ->where('oc_product.image', "<>", "")
                ->whereDate('oc_customer_wishlist.date_added', $date);
                $products = $products->whereIn('oc_customer_wishlist.customer_id', $arrCustomer);
                $products = $products->orderBy('oc_customer_wishlist.date_added', 'desc')
                ->get()
                ->groupBy('customer_id')
                ->toArray();
        }
        return $products;
    }

    /**
     * Get data from lastview
     *
     * @param [type] $date
     * @return void
     */
    public function getDataLastView($date)
    {
        $products = [];
        $arrCustomerLastView =  (new OcProductRecentView)
            ->whereDate('date', $date)
            ->groupBy('customer_id')
            ->pluck('customer_id')
            ->toArray();

        $arrCustomerWishlist =  (new OcCustomerWishlist)
        ->whereDate('date_added', $date)
        ->groupBy('customer_id')
        ->pluck('customer_id')
        ->toArray();

        $arrCustomer = array_diff($arrCustomerLastView,$arrCustomerWishlist);

        if($arrCustomer) {
            $cols = [
                'oc_product_recent_view.product_id',
                'oc_product.quantity',
                'oc_product.price',
                'oc_product.model',
                'oc_product.image',
                'oc_product.rated',
                'oc_product.rated_time',
                'oc_product_info.name',
                'oc_product_recent_view.customer_id',
            ];
            $products = (new OcProductRecentView)
                ->select($cols)
                ->join('oc_product', 'oc_product.product_id', 'oc_product_recent_view.product_id')
                ->join('oc_product_info', 'oc_product_info.product_id', 'oc_product_recent_view.product_id')
                ->where('oc_product.status', 1)
                ->whereNotNull('oc_product.image')
                ->where('oc_product.image', "<>", "")
                ->whereDate('oc_product_recent_view.date', $date);
                $products = $products->whereIn('oc_product_recent_view.customer_id', $arrCustomer);
                $products = $products->orderBy('oc_product_recent_view.date', 'desc')
                ->get()
                ->groupBy('customer_id')
                ->toArray();
        }
        return $products;
    }
}
