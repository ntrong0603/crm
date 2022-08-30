<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Events\Batch as eventBatch;
use App\Notifications\GoogleChat;
use Illuminate\Notifications\Notifiable;
use Throwable;

class Handler extends ExceptionHandler
{
    use Notifiable;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            try {
                $traceString = $exception->getTraceAsString();
                $arrayTrace = explode("\n", $traceString);
                $index = 0;
                //Get information error
                foreach ($arrayTrace as $key => $value) {
                    if (strpos($value, 'internal function]') !== false) {
                        //Get index current
                        $index = $key - 1;
                        break;
                    }
                }
                if ($index === -1) {
                    $index = 1;
                };
                $trace = $exception->getTrace();
                if (isset($trace[$index]['file'])) {
                    $file      = $trace[$index]['file'];
                    $tempName  = explode(DIRECTORY_SEPARATOR, $file);
                    $className = str_replace('.php', '', $tempName[count($tempName) - 1]);
                    $fileError = $arrayTrace[$index];
                    $request = request();
                    $url = '';
                    $ip  = '';
                    if (!empty($request)) {
                        $url = $request->fullUrl();
                        $ip  = $request->ip();
                    }
                    $error  = "URL: " . $url . " -- IP: " . $ip . PHP_EOL;
                    $error .= "*`" . $className . "`*" . PHP_EOL;
                    $error .= "*" . $exception->getMessage() . "*" . PHP_EOL;
                    $error .= $fileError . PHP_EOL;
                    //debug preve
                    if ($index > 0) {
                        $error .= $arrayTrace[$index - 1] . PHP_EOL;
                    } else {
                        $error .= $arrayTrace[$index] . PHP_EOL;
                    }
                    $error .= $exception->getMessage() . PHP_EOL;

                    crm_report($error);

                    //Update to table status batch
                    if (strpos($file, 'Commands') !== false) {
                        event(new eventBatch(['status'=>'stop', 'error' => $error]));
                    }
                } else {
                    $request = request();
                    $url = '';
                    $ip  = '';
                    if (!empty($request)) {
                        $url = $request->fullUrl();
                        $ip  = $request->ip();
                    }
                    $error  = "URL: " . $url . "  -- IP: " . $ip . PHP_EOL;
                    $error .= "*" . $exception->getMessage() . "*" . PHP_EOL;
                    crm_report($error);
                }

            } catch(\Throwable $e) {
                \Log::error($e->getMessage());
            }
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
