<?php

namespace App\Models\Df;

use Illuminate\Database\Eloquent\Model;

class MstPostalGroup extends Model
{
    protected $primaryKey = 'id';
    protected $table      = 'mst_postal_group';
    protected $connection = 'df';
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
     * Process get list prefecture
     *
     * @param array condition get data
     * @return object
     */
    public function getListPrefecture()
    {
        $col = [
            'name',
        ];
        $data = $this->select($col)->orderBy("sort_popup", "asc")->get();
        return $data;
    }
}
