<?php

/**
 * Model for mst_customer_rank table.
 *
 * @package    App\Models\Df
 * @subpackage MstCustomerRank
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.df>
 */

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;

class MstCustomerRank extends Model
{
    protected $primaryKey = 'rank_id';
    protected $connection = 'df';
    protected $table      = 'mst_customer_rank';
    const CREATED_AT      = 'in_date';
    const UPDATED_AT      = 'up_date';
    protected $guarded    = [];

    /**
     * Process update info customer
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
     * Process update or insert condition not isset
     *
     * @param array $rankID array rank_id need update or create
     * @param array $dataUpdate array data need update
     * @return void
     */
    public function insertOrUpdate($rankID, $dataUpdate)
    {
        return $this->updateOrCreate($rankID, $dataUpdate);
    }

    /**
     * Process update or insert setting rank
     *
     * @param array $dataUpdate array data need update
     * @param int $opeCD id account update or create
     * @return void
     */
    public function insertOrUpdateRank($dataUpdate, $opeCD)
    {
        if (empty($dataUpdate) || !is_array($dataUpdate) || empty($opeCD) || !is_numeric($opeCD)) {
            return null;
        }
        foreach ($dataUpdate as $key => $item) {
            $rank = $this->find($item['rank_id']);
            if (empty($rank)) {
                $this->insert([
                    'rank_id'    => $item['rank_id'],
                    'ebuy_priod' => $item['ebuy_priod'],
                    'ebuy_times' => $item['ebuy_times'],
                    'ebuy_price' => $item['ebuy_price'],
                    'in_ope_cd'  => $opeCD,
                ]);
            } else {
                $rank->rank_id    = $item['rank_id'];
                $rank->ebuy_priod = $item['ebuy_priod'];
                $rank->ebuy_times = $item['ebuy_times'];
                $rank->ebuy_price = $item['ebuy_price'];
                $rank->up_ope_cd  = $opeCD;
                $rank->save();
            }
        }
        return;
        // return $this->updateOrCreate($dataTracking, $dataUpdate);
    }
}
