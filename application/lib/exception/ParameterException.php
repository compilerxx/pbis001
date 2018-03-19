<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-17
 * Time: 下午5:31
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400; //http status code 404,200 ...
    public $msg='input param invalid'; //detail error message
    public $errorCode=10000;            //self define error code

}