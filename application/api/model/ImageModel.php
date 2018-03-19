<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 上午8:45
 */

namespace app\api\model;


use think\Model;

class ImageModel extends BaseModel
{
    protected $table = 'image';
    protected $hidden=['id','from','update_time','delete_time'];

    public function getUrlAttr($value,$data){ //获取器，参考官方文档 getFieldNameAttr，这里函数名里的Url是image 表的一个field，
        //获取器的作用是对模型实例的（原始）数据做出自动处理。

//        $finalUrl = $value;
//        if ($data['from'] == 1){  //$data 包含一条record 的所有field，'from' 是其中一个field 名
//            $finalUrl = config('setting.img_path_prefix').$value; //img 路径的拼接,tp5.1自定义的 setting.php 要直接放到config 目录下。
//        }
//        return $finalUrl;
        return $this->concatImaUrl($value,$data); //调用基类方法
    }


}