<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-17
 * Time: 上午10:11
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = 'request banner not exist';
    public $errorCode = 40000;

}