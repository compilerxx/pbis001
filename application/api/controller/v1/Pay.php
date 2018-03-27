<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 下午10:24
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'getPreOrder']  //前置函数检查权限
    ];

    public function getPreOrder($id=''){
        (new IDMustBePositiveInt()) ->goCheck();
        $pay = new PayService($id); //id 是orderid
        return $pay->pay();
//        return $pay->pay();
    }

    public function receiveNotify(){
        //微信支付成功会异步返回消息给我们服务器，我们要定义给微信的回调函数处理返回的消息否则微信会以该频率不断的发消息。
        //微信会以post 的形式调用
        //通知频率为：15/15/30/180/1800/1800/1800/1800/3600,单位：秒

        //1，检查库存量，超卖的情况
        //2，更新订单status状态
        //3，减库存
        //如果成功处理，需要返回给微信成功处理的信息，否则我们需要返回没有成功处理
        //wx 调用这个接口的方式：post；xml 格式；通知url必须为直接可访问的url不会携带参数

        $notify = new WxNotify();
        $notify->Handle(); //不能直接调用我们重写wx 的 NotifyProcess 方法，需要调用wx 要求的回调入口函数。

    }

}