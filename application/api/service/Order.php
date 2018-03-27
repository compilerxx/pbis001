<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午3:02
 */

namespace app\api\service;


use app\api\model\OrderModel;
use app\api\model\OrderProductModel;
use app\api\model\ProductModel;
use app\api\model\UserAddressModel;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    // 因为order 业务逻辑比较复杂，所以不直接在controller 里写，把逻辑提取到 service 里处理

    // 订单的商品列表，客户端传递过来的订单 products 参数
    protected $oProducts;

    // 真实的商品信息（包括库存量）
    protected $products;

    protected $uid;

    public function place($uid,$oProducts){
        //oProduct 和 products 作对比
        //products 从数据库查询出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid=$uid;

        $status = $this->getOrderStatus();
        if (!$status['pass']){
            $status['order_id'] = -1; //新增一个order_id 项
            return $status;
        }

        //开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true; //加一个field告诉客户端是否创建订单成功。

        return $order;

    }

    //
    private function createOrder($snap){ //创建订单并存入数据库

        Db::startTrans(); //数据事务处理，保证数据的完整性，和Db::commit()一起使用。
        try {

            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();

            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save(); //1,一对多的保存，先写入一的一方

            $orderID = $order->id; //record 写入数据库后就可以读了
            $create_time = $order->create_time;

            foreach ($this->oProducts as &$p){ //注意&的用法，加& 才能对数组的属性做修改操作。
                $p['order_id'] = $orderID;
            }

            $orderProduct = new OrderProductModel();
            $orderProduct->saveAll($this->oProducts); //2，然后再写入多的一方

            Db::commit(); //数据事务处理，保证数据的完整性，和 Db::startTrans()一起使用

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];

        }catch (Exception $ex){
            Db::rollback(); //数据事务处理，保证数据的完整性
            throw $ex;
        }

    }

    public static function makeOrderNo(){ //尽量避免订单号重复,定义为static 方法是因为其他地方可能会用到此方法。
        $yCode = array('A','B','','C','D','E','F','G','H','I','J');
        $orderSn = $yCode[intval(date('Y'))-2018].strtoupper(dechex(date('m'))).date('d')
            .substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    //生成订单快照
    private function snapOrder($status){
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,  //订单下面所有商品总数
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '', // 每项订单的简略标题
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress()); //把一个对象存在表的某个字段的方法不太好，如果要存一个对象最好选用noSQL类型的数据库
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        if (count($this->products)>1){
            $snap['snapName'] .='等';
        }

        return $snap;

    }

    private function getUserAddress(){
        $userAddress = UserAddressModel::where('user_id','=',$this->uid) ->find();
        if (!$userAddress){
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddress->toArray(); //查询出来的$userAddress 是一个对象，要转成数组返回
    }


    //创建一个对外的方法，供调用检查库存量，例如支付Pay 之前还要检查一下库存量
    public function checkOrderStock($orderID){
        $oProducts = OrderProductModel::where('order_id','=',$orderID)->select();
        $this->oProducts = $oProducts;  //为getOrderStatus()而准备的参数
        $this->products = $this->getProductsByOrder($oProducts); //为getOrderStatus()而准备的参数

        $status = $this->getOrderStatus(); //体会方法的复用
        return $status;

    }

    private function getOrderStatus(){
        $status = [
            'pass' => true, //订单是否通过检查
            'orderPrice' => 0,
            'totalCount' => 0, ////商品总数
            'pStatusArray' => [] //订单的所有详细信息
        ];
        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'],$oProduct['count'],$this->products);

            if (!$pStatus['haveStock']){
                $status['pass'] = false;
            }

            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'],$pStatus);
        }

        return $status;
    }

    private function getProductStatus($oPID,$oCount,$products){

        $pIndex = -1;

        $pStatus = [ //保存历史订单相关的商品具体信息
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0  //某类商品的总价格
        ];

        for ($i=0;$i< count($products);$i++){
            if ($oPID==$products[$i]['id']){
                $pIndex = $i;
            }
        }

        if ($pIndex==-1){
            //客户端传递过来的订单的product_id 有可能根本不存在
            throw new OrderException([
                'msg' =>  'id为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        }else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if ($product['stock'] - $oCount >=0 ){
                $pStatus['haveStock'] = true;
            }
        }

        return $pStatus;
    }

    //根据订单信息查找真实的商品信息
    private function getProductsByOrder($oProducts){
//        foreach ($oProducts as $oProduct){
//            //循环读取数据库，此方法不可取
//        }
        $oPIDs = [];
        foreach ($oProducts as $item){
            array_push($oPIDs,$item['product_id']);
        }
        $products = ProductModel::all($oPIDs)  //根据product id 一次把订单中的product 都读取出来
             ->visible(['id','price','stock','name','main_img_url'])->toArray();

        return $products;
    }

}

//订单中product的格式：
//    protected $products=[
//        [
//            'product_id' => 1,
//            'count' => 3
//        ],
//        [
//            'product_id' => 2,
//            'count' => 3
//        ],
//        [
//            'product_id' => 3,
//            'count' => 3
//        ]
//    ];