<?php

use App\Models\Biz\MstConfig;

/*
Config info
 */

if (!function_exists('crm_config')) {
    function crm_config($key = null, $default = null)
    {
        $allConfig = [];
        try {
            $allConfig = MstConfig::getAllconfig();
        } catch (\Exception $e) {
            //
        }
        if ($key == null) {
            return $allConfig;
        }
        if (!array_key_exists($key, $allConfig)) {
            return $default;
        } else {
            return trim($allConfig[$key]);
        }
    }
}

/**
 * Render url product
 */
if (!function_exists('crm_url_product')) {
    function crm_url_product($model, $mall = 'biz', $param = '')
    {
        $model = strtolower($model);
        if($mall == 'df') {
            return 'https://www.diyfactory.jp/shop/product/' . $model . '/'.$param;
        } else {
            return 'https://shop.diyfactory.jp/product/' . $model . '/'.$param;
        }
    }
}

/**
 * Report info
 */
if (!function_exists('crm_report')) {
    function crm_report($msg, $type = 'normal', $level = 'error')
    {
        try {
            if ($type == 'public') {
                //Post to google
                try {
                    $url = config('google.chat.webhook');
                    $data = json_encode(['text' => $msg], JSON_UNESCAPED_UNICODE);
            
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                    $response = curl_exec($curl);
                    curl_close($curl);
                } catch(\Throwable $e) {
                    $msg .= $e->getMessage();
                }
            }
            //Notify to slack
            \Log::channel('slack')->error($msg);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
        }
    }
}
