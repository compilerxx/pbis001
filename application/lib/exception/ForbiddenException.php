<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午1:04
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403; //http status code 404,200 ...
    public $msg='authority not allow'; //detail error message
    public $errorCode=10001;            //self define error code

}