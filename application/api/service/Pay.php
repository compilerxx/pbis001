<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 下午10:29
 */

namespace app\api\service;


use app\api\model\OrderModel;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use think\facade\Log;


//Loader::import() , TP5.1 已经取消该方法，可以用php内置的include或者require语法
//require __DIR__ . '/../../../extend/WxPay/WxPay.Api.php';  //__DIR__ 是指本文件Pay.php 所在的目录
//require_once __DIR__ . '/../../../extend/WxPay/WxPay.Api.php';
//include '../../../extend/WxPay/WxPay.Api.php';

include dirname(dirname(dirname(__DIR__))) . "/extend/WxPay/WxPay.Api.php";

class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID){
            throw new Exception('订单号不能为NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay(){ //如何在这里调用order 类的库存检查方法？在service/Order 类里定义一个对外的接口checkOrderStock供调用。

        //1,订单号可能不存在的情况
        //2,订单存在但和当前用户是不匹配的
        //3,订单可能已经被支付过
        //4,进行库存检查
        $this ->checkOrderValid(); //case 1~3
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID); //case4
        if (!$status['pass']){
            return $status;
        }

        return $this->makeWxPreOrder($status['orderPrice']);

    }

    private function makeWxPreOrder($totalPrice){
        //openid
        $openid = TokenService::getCurrentTokenVar('openid');
        if (!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder(); //因为引用的 WxPayUnifiedOrder() 没有命名空间，所以要在前面加'/'。
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);//微信默认该fee 的单位是分钱，我们是元为单位所以＊100
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));//给微信一个url 用于接收微信的回调通知，需要创建接收微信支付结果的接口。

        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData){
        $wxOrder = \wxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code']!='SUCCESS' ||
            $wxOrder['result_code']!='SUCCESS' ){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }
        //prepay_id 只有$wxOrder 'SUCCESS'时才会有prepay_id
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
        //return null;
        //return $wxOrder; //测试时若想看到 $wxOrder的输出到微信客户端，则用return
    }

    private function sign($wxOrder){ //生成签名
        $jsApiPayData = new \WxPayJsApiPay(); //这个类WxPayJsApiPay的作用是可以根据其生成签名。
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());

        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();//生成签名
        $rawValues = $jsApiPayData->GetValues();//将对象转换成数组形式的值
        $rawValues['paySign'] = $sign; //加一个field 把签名也return回去

        unset($rawValues['appId']); //客户端不需要'appid'，不用返回，可以删掉。

        return $rawValues;

    }

    private function recordPreOrder($wxOrder){
        //获得微信返回的prepay_id 并存入数据库可用于之后发送微信模版消息
        OrderModel::where('id','=',$this->orderID)->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    private function checkOrderValid(){
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if (!$order){    // case 1
            throw new OrderException();
        }

        if (!TokenService::isValidOperate($order->user_id)){ //case 2
            throw new TokenException([
                'msg' =>'订单与用户不匹配',
                'errorCode' =>10003
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID){ //case 3
            throw new OrderException([
                'msg' =>'订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);

        }
        $this->orderNO = $order->order_no;
        return true;
    }

}