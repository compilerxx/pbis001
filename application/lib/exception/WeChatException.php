<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-20
 * Time: 下午10:35
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400; //http status code 404,200 ...
    public $msg='call WeChat service interface failed '; //detail error message
    public $errorCode=999;            //self define error code

}