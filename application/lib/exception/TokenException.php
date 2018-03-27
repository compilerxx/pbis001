<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-21
 * Time: 上午12:35
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401; //http status code 404,200 ...
    public $msg='token have expired or invalid token'; //detail error message
    public $errorCode=10001;            //self define error code

}