<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午3:48
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404; //http status code 404,200 ...
    public $msg='order not exist, please check product id'; //detail error message
    public $errorCode=80000;            //self define error code

}