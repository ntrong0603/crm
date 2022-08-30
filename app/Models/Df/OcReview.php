<?php

/**
 * Model for oc_review table.
 *
 * @package    App\Models\Df
 * @subpackage OcReview
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     Pham Son <songoku_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;
use DB;

class OcReview extends Model
{
    protected $primaryKey = 'review_id';
    protected $connection = 'df';
    protected $table      = 'oc_review';
    const CREATED_AT      = 'date_added';
    const UPDATED_AT      = 'date_modified';
    protected $guarded    = [];

    /**
     * Get product review by customer
     *
     * @param array $arrProduct
     * @return object
     */
    public function getDataProduct($arrProduct = [])
    {
        $cols = [
            'product_id',
            DB::raw("Count(product_id) as count_product"),
            DB::raw("AVG(rating) as avg_rating"),
        ];

        $result = $this->select($cols)
            ->whereIn('product_id', $arrProduct)
            ->groupBy('product_id')
            ->get();

        return $result;
    }
}
