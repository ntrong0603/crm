<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;
use App\Models\Biz\LogQueryProcessBatches;
use App\Models\Biz\LogQueryProcessScreens;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
            require_once $filename;
        }
        DB::listen(function ($query) {
            $signature = '';
            $mall = session('mall');
            if (empty(app('request')->route())) {
                $argv = $_SERVER['argv'] ?? [];
                if (isset($argv[1])) {
                    $signature = $argv[1];
                } else {
                    $signature = '';
                    Log::channel('slack')->error("*Error signature*");
                }
                $flag = 1;
            } else {
                $arrayRequest = app('request')->route()->getAction();
                if (isset($arrayRequest['controller'])) {
                    $signature = $arrayRequest['controller'];
                    $flag = 2;
                } else {
                    $flag = 3;
                }
            }
            $queryList = $query->sql;
            $tmpQuery  = substr($queryList, 0, 100);
            $bindList  = $query->bindings;
            $ignoreTableInsert = "(?!log_query_process_batches)(?!log_query_process_screens)(?!dt_mail_sent)(?!dt_mail_sent_content).";
            $ignoreTableUpdate = "(?!log_query_process_batches)(?!log_query_process_screens)(?!dt_mail_sent_content).";
            $string = "/^update ($ignoreTableUpdate)*$|^insert ($ignoreTableInsert)*$/";
            if (preg_match($string, $tmpQuery)) {
                foreach ($bindList as $binding) {
                    $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
                    $queryList = preg_replace('/\?/', $value, $queryList, 1);
                }

                if ($flag === 1) {
                    LogQueryProcessBatches::insert(array('class_action' => $signature, 'query_string' => $queryList));
                } else {
                    LogQueryProcessScreens::insert(array('class_action' => $signature, 'query_string' => $queryList, 'mall' => $mall));
                }
            }
        });
    }
}
