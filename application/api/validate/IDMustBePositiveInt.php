<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-16
 * Time: 下午10:42
 */

namespace app\api\validate;


use think\Validate;

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
      'id' => 'require|isPositiveInteger',
     // 'num' => 'in:1,2,3'
    ];

    protected $message = [    //对应$rule，验证不过就提示的message
        'id' => 'id 必须是正整数'
    ];

    // 自定义验证规则  ==> 提取到基类
//    protected function isPositiveInteger($value,$rule,$data=[],$field)
//    {
//        //return $rule == $value ? true : '名称错误';
//
//        if (is_numeric($value) && is_int($value +0) && ($value +0)> 0){
//            return true;
//        }else{
//            return $field.'必须是正整数';
//        }
//    }

}