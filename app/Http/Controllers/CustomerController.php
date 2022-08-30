<?php

/**
 * Customer Management Controller
 *
 * @package     App\Http\Controllers
 * @subpackage  CustomerController
 * @copyright   Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author      Sesshomaru <sesshomaru_vn@monotos.biz>
 */

namespace App\Http\Controllers;

use App\Models\Biz\MstPostal;
use App\Models\Df\MstPostal as MstPostalDf;
use App\Models\Biz\MstPostalGroup;
use App\Models\Df\MstPostalGroup as MstPostalGroupDf;
use App\Models\Biz\OcAddress;
use App\Models\Df\OcAddress as OcAddressDf;
use App\Models\Biz\OcCustomer;
use App\Models\Df\OcCustomer as OcCustomerDf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    private $mCustomer;
    private $mPostal;
    private $mPostalGroup;
    private $oAddress;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mCustomer = new OcCustomerDf();
                $this->mPostal = new MstPostalDf();
                $this->mPostalGroup = new MstPostalGroupDf();
                $this->oAddress = new OcAddressDf();
            } else {
                $this->mCustomer = new OcCustomer();
                $this->mPostal = new MstPostal();
                $this->mPostalGroup = new MstPostalGroup();
                $this->oAddress = new OcAddress();
            }
            return $next($request);
        });
    }

    /**
     * View index
     */
    public function index()
    {
        $prefectures = $this->mPostalGroup->getListPrefecture();
        return view("admin.customer.list", ['prefectures' => $prefectures]);
    }

    /**
     * Process get list customer
     *
     * @param int $limit record customer need get
     * @return json
     */
    public function getListCustomers(Request $request)
    {
        $limit = $request->get('perPage');
        if (!is_numeric($limit)) {
            $limit = 25;
        }
        $datas = $this->mCustomer->getData($request->all(), $limit);
        return response($datas);
    }

    /**
     * Show form edit infor customer
     *
     * @return View
     */
    public function editCustomer($id)
    {
        $urlPrevious = url()->previous();
        if (!\Str::contains($urlPrevious, ['customer']) || \Str::contains($urlPrevious, ['customer/add', 'customer/edit'])) {
            $urlPrevious = route('customer.list');
        }
        $citys = $this->mPostal->getListPrefecture();
        $data = $this->mCustomer->getInfoCustomer($id);
        $data->postcode = str_replace("-", "", $data->postcode);
        return view("admin.customer.edit_customer", [
            'citys'        => $citys,
            'customer'     => $data,
            'urlPrevious'  => $urlPrevious,
        ]);
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
        $customer = $this->mCustomer->find($request->customer_id);
        if (empty($customer)) {
            $data['error'] = "空データです。";
        } else {
            // check validate
            $arrValidate = [
                'customer_id'    => 'required|min:1|max:30',
                'lastname'       => 'required|min:1|max:30',
                'firstname'      => 'required|min:1|max:20',
                'lastname_kana'  => 'required|regex:/^[ァ-ヶｦ-ﾟー]+$/u|min:1|max:32',
                'firstname_kana' => 'required|regex:/^[ァ-ヶｦ-ﾟー]+$/u|min:1|max:32',
                'email'          => 'required|email|min:8|max:50',
                'postcode'       => 'required|regex:/^[0-9]{7}/|size:7|exists:"' . MstPostal::class . '",postal_code',
                'city'           => 'required',
                'address_1'      => 'required',
                'address_2'      => 'required',
            ];
            $arrMess = [
                'postcode.regex'  => '郵便番号が正しくありません',
                'postcode.exists' => '郵便番号が正しくありません',
            ];

            $this->validate(
                $request,
                $arrValidate,
                $arrMess,
            );
            $inOpeCd = Auth::user()->id;
            $customer->customer_id    = $request->customer_id;
            $customer->lastname       = $request->lastname;
            $customer->firstname      = $request->firstname;
            $customer->lastname_kana  = $request->lastname_kana;
            $customer->firstname_kana = $request->firstname_kana;
            $customer->sex            = $request->sex;
            $customer->birthday       = $request->birthday;
            $customer->email          = $request->email;
            $customer->telephone      = $request->telephone;
            $customer->fax            = $request->fax;
            $customer->newsletter     = $request->newsletter;
            $customer->status         = $request->status;
            $customer->up_ope_cd      = $inOpeCd;
            $customer->save();

            $address = $this->oAddress->find($customer->address_id);
            $address->postcode        = substr($request->postcode, 0, 3) . "-" . substr($request->postcode, -4);
            $address->city            = $request->city;
            $address->address_1       = $request->address_1;
            $address->address_2       = $request->address_2;
            $address->up_ope_cd       = $inOpeCd;
            $address->save();

            $data['success'] = "Success";
        }
        // dd(back());
        return response($data);
        // return redirect()->back();
    }

    /**
     * Get info post code
     *
     * @param request $request parameter get data
     * @return json
     */
    public function getPostCode(Request $request)
    {
        if ($request['postalCode'] && strlen(trim($request['postalCode'])) == 7) {
            $data = $this->mPostal->getPostCode(trim($request['postalCode']));
            if (!empty($data)) {
                return $data;
            }
        }
        return response([
            "errors" => [
                'postcode' => [
                    '郵便番号が正しくありません',
                ],
            ]
        ]);
    }

    /**
     * Process delete customer
     *
     * @param int $id ID customer
     * @return json
     */
    public function deleteCustomer($id)
    {
        $this->mCustomer->find($id)->delete();
        return response(['success' => "Success"]);
    }
}
