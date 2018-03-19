<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-19
 * Time: 上午1:16
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = 'request category not exist, please check param';
    public $errorCode = 50000;


}