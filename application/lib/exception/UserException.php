<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-23
 * Time: 下午9:34
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404; //http status code 404,200 ...
    public $msg='current user not exist.'; //detail error message
    public $errorCode=60000;            //self define error code

}