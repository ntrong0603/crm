<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biz\MstBatchStatus;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.batch.index");
    }

    /**
     * Get list data
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatas()
    {
        return (new MstBatchStatus)->getBatch(session('mall'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activeDisactive(Request $request)
    {
        if ($request->ajax() === true) {
            $data = (new MstBatchStatus)->find($request->signature);
            if (!empty($data)) {
                $data->is_active = $data->is_active === 1 ? 0 : 1;
                $data->save();
                return ['message' => $data->is_active === 1 ? "Acctive success" : "Disactive success"];
            }
        }
    }

    public function reset(Request $request)
    {
        if ($request->ajax() === true) {
            $data = (new MstBatchStatus)->find($request->signature);
            if (!empty($data)) {
                $data->error_message = null;
                $data->status_flag   = 0;
                $data->save();
                return ['message' => "Reset success"];
            }
        }
    }

    public function execute(Request $request)
    {
        if ($request->ajax() === true) {
            $data = (new MstBatchStatus)->find($request->signature);
            if (!empty($data)) {
                $name = $request->signature;
                $path = base_path('artisan');
                $cmd  = "php $path $name";
                if (substr(php_uname(), 0, 7) == "Windows") {
                    $cmd .= " >NUL 2>NUL";
                    pclose(popen('start /B cmd /C "' . $cmd . '"', 'r'));
                } else {
                    exec($cmd . " > /dev/null 2>/dev/null &");
                }
                $data->error_message = null;
                $data->status_flag   = 1;
                $data->save();
                return ['message' => "Batch {$request->signature} is executing"];
            }
        }
    }
}
