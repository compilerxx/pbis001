<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-16
 * Time: 下午9:42
 */

namespace app\api\validate;


use think\Validate;

class TestValidate extends Validate
{
    protected $rule =[
        'name' => 'require|max:8',
        'email' => 'email'
    ];

    protected $message =[
        'name.require' => '必须输入名称',
        'name.max' => '名称不能超过8位',
        'email' => '邮箱格式有误'
    ];

}