<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午11:40
 */

namespace app\api\model;


class OrderModel extends BaseModel
{
    protected $table = 'order';
    protected $pk = 'id';
    protected $hidden=['user_id','delete_time','update_time'];

    protected $autoWriteTimestamp = true;
    //让数据表的create_time,delete_time,update_time 能自动更新，但是要用model 的方式更新数据库才有效。

    public function getSnapItemsAttr($value){ //还记得getxxxAttr 吗？
        if (empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        if (empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public static function getSummaryByUser($uid, $page=1, $size=15){

        $pagingData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]); //分页查询,简洁模式不计算记录数。paginate 返回的是一个对象

        return $pagingData;
    }
}