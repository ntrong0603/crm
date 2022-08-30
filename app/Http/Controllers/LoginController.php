<?php

/**
 * Controller LoginController
 *
 * @package    App\Http\Controllers
 * @subpackage LoginController
 * @copyright  Copyright (c) 2019 RiverCrane! Corporation. All Rights Reserved.
 * @author     Le Trong<le.trong@rivercrane.com.vn>
 */

namespace App\Http\Controllers;

use App\Models\Biz\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function indexFormLogin()
    {
        if (!Auth::check()) {
            return view('admin.auth.login');
        } else {
            return redirect()->route('admin');
        }
    }
    /**
     * Process check info login
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    public function signIn(Request $request)
    {
        $this->validate(
            $request,
            [
                'user_name' => 'required',
                'password'  => 'required',
            ]
        );
        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password], $request->remember)) {
            //luu ngay va dia chi ip neu dang nhap thanh cong
            $user = Users::find(Auth::user()->id);
            $user->last_login_ip = $request->getClientIp();
            $user->last_login_at = now();
            $user->save();

            $messages = trans('messages.loginsuccess');
            return redirect()->route('admin')->with('success', $messages);
        } else {
            $messages = trans('messages.loginerror');
            return redirect()->back()
                ->withInput()
                ->with('error_login', $messages);
        }
    }
    /**
     * Process check logout
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    public function logOut(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('formLogin');
        } else {
            return redirect('/');
        }
    }
}
