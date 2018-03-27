<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 下午12:06
 */

namespace app\api\model;


class OrderProductModel extends BaseModel
{
    //order 和product 之间的多对多的关联中间模型
    protected $table = 'order_product';


}