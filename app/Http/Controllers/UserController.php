<?php

/**
 * Controller UserController
 *
 * @package    App\Http\Controllers
 * @subpackage UserController
 * @copyright  Copyright (c) 2019 RiverCrane! Corporation. All Rights Reserved.
 * @author     Le Trong <le.trong@rivercrane.com.vn>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biz\Users;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the list the product
     * @return View
     */

    public function userList()
    {
        return view('admin.user.list');
    }

    public function getListUser(Request $request)
    {
        $param = $request->all();
        if (!empty($param['limit'])) {
            $limit = $param['limit'];
        } else {
            $limit = 25;
        }
        $users = new Users();
        $idAuth = Auth::user()->id;

        if ($idAuth !== 1) {
            $users = $users->where('id', "<>", 1);
        }

        $users = $users->paginate($limit);
        $data['listUser']   = $users;
        //get permission orther
        foreach ($data['listUser'] as $item) {
            $item['format_created_at'] = date('Y-m-d H:i:s', strtotime($item['created_at']));
            $item['format_updated_at'] = date('Y-m-d H:i:s', strtotime($item['updated_at']));
        }
        return response($data, 200);
    }

    /**
     * Show form entry info user
     * @return View
     */
    public function addUser()
    {
        return view('admin.user.add_user');
    }

    /**
     * Process check and add info user
     *
     * @param Request $request
     * @return redirect form Add and return messenges
     */
    public function processAddUser(Request $request)
    {
        $data = array();

        // check validate
        $this->validate(
            $request,
            [
                'name'                     => 'required|min:3|max:30',
                'email'                    => 'required|email|unique:App\Models\Biz\Users,email|min:8|max:50',
                'user_name'                => 'required|min:5|max:20|unique:App\Models\Biz\Users,user_name',
                'password_user'            => 'required|min:6|max:24',
                'password_user_confim'     => 'required|same:password_user',
            ],
            [
                "password_user.regex"   => trans('messages.error_regex_pass'),
            ]
        );
        $inOpeCd = Auth::user()->id;
        $user = new Users;
        $user->name = strip_tags($request->name);
        $user->email = strip_tags($request->email);
        $user->user_name = strip_tags($request->user_name);
        $user->password = bcrypt($request->password_user);
        $user->in_ope_cd = $inOpeCd;
        $user->save();
        $data['success'] = "Success";
        return response($data, 200);
    }

    /**
     * Show form edit info user
     * @return View
     */
    public function editUser($id)
    {
        return view('admin.user.edit_user');
    }

    /**
     * Get info user
     * @return json
     */
    public function getUser($id)
    {
        $user = Users::find($id);
        $data['user'] = $user;
        return response($data, 200);
    }

    /**
     * Process check and add info user
     *
     * @param Request $request
     * @return redirect form Add and return messenges
     */
    public function processEditUser($id, Request $request)
    {
        $data   = array();
        $idAuth = Auth::user()->id;
        $user   = Users::find($request->id);
        if ($request->id == 1 && $idAuth != 1) {
            $data['error'] = "Not permission edit";
        } else {
            if (empty($user)) {
                $data['error'] = "空データです。";
            } else {
                // check validate
                $arrValidate = [
                    'name'      => 'required|min:3|max:30',
                ];
                $arrMess = [];
                if ($request->user_name != $user->user_name) {
                    $arrValidate['user_name'] = 'required|min:5|max:20|unique:App\Models\Biz\Users,user_name';
                }
                if ($request->email != $user->email) {
                    $arrValidate['email'] = 'required|email|unique:App\Models\Biz\Users,email|min:8|max:50';
                }
                if ($request->password_user != '' || $request->password_user_confim != '') {
                    $arrValidate['password_user']        = 'required|min:6|max:24';
                    $arrValidate['password_user_confim'] = 'required|same:password_user';
                    $arrMess["password_user.regex"]      = trans('messages.error_regex_pass');
                    $user->password = bcrypt($request->password_user);
                }

                $this->validate(
                    $request,
                    $arrValidate,
                    $arrMess,
                );
                $inOpeCd = Auth::user()->id;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->user_name = $request->user_name;
                $user->up_ope_cd = $inOpeCd;
                $user->save();
                $data['success'] = "Success";
            }
        }
        return response($data, 200);
    }

    /**
     * Process delete user
     *
     * @param int $id ID user
     * @return redirect form Add and return messenges
     */
    public function processDeleteUser($id)
    {
        $data = array();
        if ($id == 1) {
            $data['error'] = "Can't delete account admin";
        } else {
            if ($id != Auth::user()->id) {
                Users::find($id)->delete();
                $data['success'] = "Success";
            } else {
                $data['error'] = "Can't delete account login";
            }
        }
        return $data;
    }
}
