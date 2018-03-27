<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午1:29
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\OrderModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;


class Order extends BaseController
{
    //用户选择商品后，向api提交商品相关信息
    //api接收到信息后，检查订单商品的库存
    //有库存，把订单数据存入数据库＝下单成功，返回客户端消息，告诉客户可以支付了
    //调用支付接口，进行支付
    //还需要再次进行库存检查
    //服务器就可以调用wx支付接口进行支付
    //小程序客户端会根据服务器返回的结果拉起微信支付
    //wx会返回一个支付结果（异步）
    //成功：也需要进行库存量的检查
    //成功：进行库存量扣除

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'placeOrder'],  //前置函数检查下单权限，管理员账号没权限做下单
        'checkPrimaryScope' => ['only' => 'getDetail, getSummaryByUser']
    ];

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @url bis.com/api/v1//order/by_user?page=1&size=3  //在postman 的header 传入 token
     */
    public function getSummaryByUser($page=1,$size=15){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();

        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty()){
            //return null;
            //throw new Exception();
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }

        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])
            ->toArray();

        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    public function getDetail($id){
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

    public function placeOrder(){

        (new OrderPlace())->goCheck();
        $products = input('post.products/a'); //助手函数input 获得post 过来的参数，加'/a' 才能获得数组
        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;

    }

}