<?php

/**
 * Route web
 *
 * @copyright  Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author     sesshomaru <seshomaru_vn@monotos.biz>
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin');
    } else {
        return redirect()->route('formLogin');
    }
});

//Image tracking open mail
Route::get('/blank.gif', 'TrackingController@imageTracking');

//Link tracking click
Route::get('/url-process', 'TrackingController@urlTracking')->name('url.process');

//Email unsubemail
Route::group(
    ['prefix' => 'email'],
    function () {
        Route::get('/rating-product', 'TrackingController@ratingProduct')->name('rating-product');
        Route::post('/review-product', 'TrackingController@reviewProduct')->name('review-product');
    }
);


// login - logout
Route::group(
    ['prefix' => 'login'],
    function () {
        Route::get('/', 'LoginController@indexFormLogin')->name('formLogin');
        Route::post('/', 'LoginController@signIn')->name('postForm');
        Route::get('logout', 'LoginController@logOut');
    }
);
Route::group(['prefix' => 'admin', 'middleware' => ['authAdmin']], function () {

    //Language
    Route::get('mall/{mall}', function ($mall) {
        session(['mall' => $mall]);
        return back();
    })->name('switch.mall');

    Route::get('/', function () {
        return view('admin.dashboard.index');
    })->name('admin');

    //user
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@userList')->name('user.list');
        Route::get('/add', 'UserController@addUser')->name('user.add');
        Route::post('/add', 'UserController@processAddUser');
        Route::get('/edit/{id}', 'UserController@editUser')->name('user.edit');
        Route::post('/edit/{id}', 'UserController@processEditUser');
        Route::post('/delete/{id}', 'UserController@processDeleteUser')->name('user.delete');
        Route::get('/detail/{id}', 'UserController@getUser')->name('user.detail');
        Route::get('/get-list-user', 'UserController@getListUser')->name('user.getListUser');
    });

    //Screen customer management
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', 'CustomerController@index')->name('customer.list');
        Route::get('/get-list-customer', 'CustomerController@getListCustomers')->name('customer.getList');
        Route::get('/edit/{id}', 'CustomerController@editCustomer')->name('customer.edit');
        Route::post('/save', 'CustomerController@save')->name('customer.save');
        Route::post('/delete/{id}', 'CustomerController@deleteCustomer')->name('customer.delete');
        Route::get('/get-postal-code', 'CustomerController@getPostCode')->name('customer.getPostalCode');
        Route::get('/get-infor-customer', 'CustomerController@getInforCustomer')->name('customer.getInforCustomer');
    });

    //Screen customer rank
    Route::group(['prefix' => 'customer-rank'], function () {
        Route::get('/', 'CustomerRankController@index')->name('customer-rank.setting');
        Route::post('/save', 'CustomerRankController@save')->name('customer-rank.save');
    });

    //Screen customer move rate
    Route::group(['prefix' => 'customer-move-rate'], function () {
        Route::get('/', 'CustomerMoveRateController@index')->name('customer-move-rate.chart');
        Route::get('/get-list-customer-move-rate', 'CustomerMoveRateController@getData')
            ->name('customer-move-rate.getData');
    });

    //Screen customer rank analisys
    Route::group(['prefix' => 'customer-rank-analisys'], function () {
        Route::get('/', 'CustomerRankAnalisysController@index')->name('customer-rank-analisys.chart');
        Route::get('/get-list-customer-rank-analisys', 'CustomerRankAnalisysController@getData')
            ->name('customer-rank-analisys.getData');
    });

    //Screen ltv analisys
    Route::group(['prefix' => 'ltv-analisys'], function () {
        Route::get('/', 'LtvAnalisysController@index')->name('ltv-analisys.chart');
        Route::get('/get-list-ltv-analisys', 'LtvAnalisysController@getData')->name('ltv-analisys.getData');
        Route::get('/get-data-chart', 'LtvAnalisysController@getDataChart')->name('ltv-analisys.getDataChart');
    });

    //Screen RFM threshold threshold
    Route::group(['prefix' => 'rfm-threshold-setting'], function () {
        Route::get('/', 'RFMThresholdController@index')->name('rfm-threshold-setting.setting');
        Route::get('/get-data', 'RFMThresholdController@getData')->name('rfm-threshold-setting.getData');
        Route::post('/save', 'RFMThresholdController@save')->name('rfm-threshold-setting.save');
    });

    //Mail setting
    Route::group(['prefix' => 'scenario'], function () {
        Route::get('/', 'ScenarioController@index')->name('scenario');

        Route::get('/add', 'ScenarioController@viewAdd')->name('scenario.viewAdd');
        Route::get('/add/spot', 'ScenarioController@viewAddSpot')->name('scenario.viewAddSpot');

        Route::get('/edit/{id}', 'ScenarioController@viewEdit')->name('scenario.viewEdit');
        Route::get('/edit/spot/{id}', 'ScenarioController@viewEdit')->name('scenario.viewEditSpot');

        Route::post('/save', 'ScenarioController@save')->name('scenario.save');
        Route::post('/copy/{id}', 'ScenarioController@copy')->name('scenario.copy');
        Route::post('/delete/{id}', 'ScenarioController@delete')->name('scenario.delete');

        Route::get('/search-product', 'ScenarioController@searchProduct')->name('scenario.searchProduct');
        Route::get('/get-data', 'ScenarioController@getData')->name('scenario.getData');
        Route::post('/change-status-mail-setting', 'ScenarioController@changeStatusMailSeting')
            ->name('scenario.changeStatusMailSeting');
    });
    //Mail template
    Route::group(['prefix' => 'mail-template'], function () {
        Route::get('/', 'MailTemplateController@index')->name('mail-template');
        Route::get('/add', 'MailTemplateController@viewAdd')->name('mail-template.viewAdd');
        Route::get('/edit/{id}', 'MailTemplateController@viewEdit')->name('mail-template.viewEdit');

        Route::post('/convert', 'MailTemplateController@convert')->name('mail-template.convert');
        Route::post('/save', 'MailTemplateController@save')->name('mail-template.save');
        Route::get('/get-data', 'MailTemplateController@getData')->name('mail-template.getData');
        Route::get('/get-template', 'MailTemplateController@getTemplate')->name('mail-template.getTemplate');

        Route::post('/copy/{id}', 'MailTemplateController@copy')->name('mail-template.copy');
        Route::post('/delete/{id}', 'MailTemplateController@delete')->name('mail-template.delete');

        Route::get('/review', 'MailTemplateController@review')->name('mail-template.review');
        //Lưu tạm thời design để preview
        Route::post('/save-provisional', 'MailTemplateController@saveProvisional')
            ->name('mail-template.saveProvisional');
        Route::post('/send-mail-test', 'MailTemplateController@sendMailTest')->name('mail-template.send-mail-test');
    });

    /*Mail Effect Measurement*/
    Route::group(['prefix' => 'mail-effect-meas'], function () {
        Route::get('/', 'MailEffectMeaController@index')->name('mail-effect');
        Route::get('/data-list', 'MailEffectMeaController@getListData')->name('mail-effect.list');
    });

    //Mail schedule
    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/', 'ScheduleController@index')->name('schedule');
        Route::get('/edit/{id}', 'ScheduleController@viewEdit')->name('schedule.viewEdit');
        Route::post('/save', 'ScheduleController@save')->name('schedule.save');
        Route::get('/get-list', 'ScheduleController@getListData')->name('schedule.getListData');
    });

    //Dashboard
    Route::group(['prefix' => 'dashBoard'], function () {
        Route::get('/', 'DashBoardController@getDatas')->name('dashBoard.getData');
    });

    //Batch management
    Route::group(['prefix' => 'batch'], function () {
        Route::get('/', 'BatchController@index')->name('batch');
        Route::get('/get-datas', 'BatchController@getDatas')->name('batch.getDatas');
        Route::post('/active-disactive', 'BatchController@activeDisactive')->name('batch.activeDisactive');
        Route::post('/reset', 'BatchController@reset')->name('batch.reset');
        Route::post('/execute', 'BatchController@execute')->name('batch.execute');
    });
});
