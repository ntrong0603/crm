<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biz\MstCustomerThreshold;
use App\Models\Df\MstCustomerThreshold as MstCustomerThresholdDf;
use Illuminate\Support\Facades\Auth;

class CustomerRankController extends Controller
{
    private $mCustThres;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mCustThres = new MstCustomerThresholdDf();
            } else {
                $this->mCustThres = new MstCustomerThreshold();
            }
            return $next($request);
        });

    }

    /**
     * Show form entry info user
     * @return View
     */
    public function index()
    {
        $data['dataThreshold'] = $this->mCustThres->getData();
        return view('admin.customer-rank.setting')->with($data);
    }

    /**
     * Process add user
     *
     * @param Request $request
     * @return redirect form Add and return messenges
     */
    public function save(Request $request)
    {
        $data = array();
        // check validate
        $this->validate(
            $request,
            [
                'new_to_stable_value'     => 'required|integer|min:0',
                'trend_to_exc_value'      => 'required|integer|min:0',
                'priod_to_secession'      => 'required|integer|min:0',
                'sta_exc_threshold_price' => 'required|integer|min:0',
            ],
        );
        $dataUpdate = request()->all();
        $inOpeCd = Auth::user()->id;
        $dataUpdate['up_ope_cd'] = $inOpeCd;
        $this->mCustThres->updateData($dataUpdate);
        $data['success'] = "Success";
        return response($data, 200);
    }
}
