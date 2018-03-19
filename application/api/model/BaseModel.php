<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 下午12:31
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    protected function concatImaUrl($value,$data){
        //把ImageModel 的 获取器提取到基类，可以应用到其他数据表model（是要有'Url'和 'from' field的数据表）

        $finalUrl = $value;
        if ($data['from'] == 1){  //$data 包含一条record 的所有field，'from' 是其中一个field 名
            $finalUrl = config('setting.img_path_prefix').$value; //img 路径的拼接,tp5.1自定义的 setting.php 要直接放到config 目录下。
        }
        return $finalUrl;
    }


}