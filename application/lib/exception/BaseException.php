<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-17
 * Time: 上午10:07
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    public $code = 400; //http status code 404,200 ...
    public $msg='input param invalid'; //detail error message
    public $errorCode=10000;            //self define error code


    public function __construct($param=[]) //构造函数的固定写法 __construct()
    {
        if (!is_array($param)){
            return;
//            throw new Exception('construct function param must be array');
        }
        if (array_key_exists('code',$param)){
            $this->code = $param['code'];
        }
        if (array_key_exists('msg',$param)){
            $this->msg = $param['msg'];
        }
        if (array_key_exists('errorCode',$param)){
            $this->errorCode = $param['errorCode'];
        }
    }

}

//999 not clear error
// 1  general error
// 2  商品类error
// 3  主题类error
// 4  banner type error
// 5  类目类error
// 6  用户类error
// 8  订单类error

//10000 通用参数错误
//10001 资源未找到
//10002 未授权（令牌不合法）
//10003 尝试非法操作
//10004 授权失败（第三方应用账号登录失败）
//10005 授权失败（服务器缓存异常）
//
//20000 请求商品不存在
//
//30000 请求主题不存在
//
//40000 Banner 不存在
//
//50000 类目不存在
//
//60000 用户不存在
//60001 用户地址不存在
//
//
//70000
//80000 订单不存在
//80001 订单中对商品不存在，可能已删除
//80002 订单还没支付，却尝试发货
//80003 订单已支付过
//




