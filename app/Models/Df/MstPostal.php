<?php

/**
 * Model for mst_postal table.
 *
 * @package    App\Models\Df
 * @subpackage MstPostal
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;

class MstPostal extends Model
{
    protected $primaryKey = 'index';
    protected $table      = 'mst_postal';
    protected $connection = 'df';
    const CREATED_AT      = 'date_added';
    const UPDATED_AT      = 'date_modified';
    protected $guarded    = [];

    /**
     * @param  array condition $where
     * @param  array update $data
     * @return boolean
     */
    public function updateData($where, $data)
    {
        $result = $this->where($where)
            ->update($data);
        return $result;
    }

    /**
     * Process get data postal
     *
     * @param array condition get data
     * @return object
     */
    public function getData()
    {
        $col = [
            'prefecture',
            'city',
            'sub_address',
            'full_address',
            'add_ship_charge',
        ];
        $data = $this->select($col)->get();
        return $data;
    }

    /**
     * Process get list prefecture
     *
     * @param array condition get data
     * @return object
     */
    public function getListPrefecture()
    {
        $col = [
            'prefecture',
        ];
        $data = $this->select($col)->groupBy('prefecture')->get();
        return $data;
    }

    /**
     * Process get list city
     *
     * @param array condition get data
     * @return object
     */
    public function getListCity()
    {
        $col = [
            'city',
        ];
        $data = $this->select($col)->groupBy('city')->get();
        return $data;
    }

    /**
     * Process get info post code
     *
     * @param string $postal postal code
     * @return object
     */
    public function getPostCode($postal)
    {
        $col = [
            'prefecture',
            'city',
            'sub_address',
            'full_address',
            'add_ship_charge'
        ];
        $data = $this->select($col)->where('postal_code', $postal)->first();
        return $data;
    }
}
