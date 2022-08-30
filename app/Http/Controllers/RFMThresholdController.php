<?php

namespace App\Http\Controllers;

use App\Models\Biz\MstCustomerRank;
use App\Models\Df\MstCustomerRank as MstCustomerRankDf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RFMThresholdController extends Controller
{
    private $mCustRank;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mCustRank = new MstCustomerRankDf();
            } else {
                $this->mCustRank = new MstCustomerRank();
            }
            return $next($request);
        });

    }
    /**
     * View index
     */
    public function index()
    {
        return view("admin.rfm-threshold-setting.setting");
    }

    /**
     * Process get data setting
     * @return json
     */
    public function getData()
    {
        $result = [];
        // rank a
        $col = [
            'rank_id',
            'ebuy_priod',
            'ebuy_times',
            'ebuy_price',
        ];
        $result = $this->mCustRank->select($col)->get()->toArray();
        return response()->json($result);
    }

    /**
     * Process save data
     *
     * @param request $request parameter data need save
     * @return json
     */
    public function save(Request $request)
    {
        $data = array();
        $this->validate(
            $request,
            [
                'arank_ebuy_priod' => 'required|integer|min:0',
                'arank_ebuy_times' => 'required|integer|min:0',
                'arank_ebuy_price' => 'required|integer|min:0',
                'brank_ebuy_priod' => 'required|integer|min:0',
                'brank_ebuy_times' => 'required|integer|min:0',
                'brank_ebuy_price' => 'required|integer|min:0',
                'crank_ebuy_priod' => 'required|integer|min:0',
                'crank_ebuy_times' => 'required|integer|min:0',
                'crank_ebuy_price' => 'required|integer|min:0',
                'drank_ebuy_priod' => 'required|integer|min:0',
                'drank_ebuy_times' => 'required|integer|min:0',
                'drank_ebuy_price' => 'required|integer|min:0',
                'erank_ebuy_priod' => 'required|integer|min:0',
                'erank_ebuy_times' => 'required|integer|min:0',
                'erank_ebuy_price' => 'required|integer|min:0',
            ],
        );
        $data = $request->all();
        // array chart rank a -> e <=> rank 1 -> 5
        $arrRank = ['e', 'd', 'c', 'b', 'a'];
        $dataUpdate = [];
        for ($index = 5; $index >= 1; $index--) {
            $item               = [];
            $item['rank_id']    = $index;
            $item['ebuy_priod'] = $data[$arrRank[$index - 1] . 'rank_ebuy_priod'];
            $item['ebuy_times'] = $data[$arrRank[$index - 1] . 'rank_ebuy_times'];
            $item['ebuy_price'] = $data[$arrRank[$index - 1] . 'rank_ebuy_price'];
            $dataUpdate[]       = $item;
        }
        $inOpeCd = Auth::user()->id;
        $this->mCustRank->insertOrUpdateRank($dataUpdate, $inOpeCd);
        $data['success'] = "Success";
        return response($data, 200);
    }
}
