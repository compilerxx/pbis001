<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-17
 * Time: 上午10:05
 */

namespace app\lib\exception;


use Exception;             //not   think\Exception
use think\exception\Handle;
use think\facade\Config;
use think\facade\Log;
use think\facade\Request;


class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //private $switch =true;

    //override render() method,
    // need to update config item :  'exception_handle'       => 'app\lib\exception\ExceptionHandler'
    public function render(Exception $e)
        //这里传入的参数是 \Exception 基类而不是 think\Exception 类，
        //因为如果用think\Exception 而发生HttpException的话 则不能正常抛出tp5框架的异常
        //think\Exception 和 HttpException 都是继承于\Exception 基类
    {

        if ($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;


        }else{
            $switch = Config::get('app_debug');

            if($switch){
                return parent::render($e);

            }else{
                $this->code = 500;
                $this->msg = 'server issue';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }

        }

        $request = Request::instance();
        $result =[
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];

        return json($result,$this->code);
    }

    private function recordErrorLog(Exception $e){
        Log::init([
            'type' => 'File',
            'level' =>['error']
        ]);
        Log::record($e->getMessage(),'error');
    }

}