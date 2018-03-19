<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 上午8:07
 */

namespace app\api\model;


use think\Db;
use think\Model;

class BannerModel extends BaseModel
{
    protected $table ='banner'; //override the var $table to map the actual table name of database
    protected $pk ='id';
    protected $hidden=['update_time','delete_time'];

    public function items(){  //模型Model关联
        return $this->hasMany('BannerItemModel','banner_id','id');
        //foreignkey or localkey 需要看文档具体方法如何填
    }

    public static function getBannerById($id){

//        $result = Db::table('banner_item')
//            -> where(function ($query)use($id){    //闭包函数
//                $query->where('banner_id','=',$id);
//            })->select();
        $banner = self::with(['items','items.img'])->find($id); //嵌套关联
//        $banner->hidden(['update_time','delete_time']);

//        return $result;
        return $banner;
    }

}