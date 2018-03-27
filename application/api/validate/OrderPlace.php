<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午2:26
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
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

    protected $rule=[
        'products' => 'checkProducts'  //checkProducts 是特别的验证规则不是通用的，所以不需要放到BaseValidate 基类中
    ];

    protected $singleRule=[ //定义product 数值子项的验证规则
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    protected function checkProducts($values){
        if (!is_array($values)){
            throw new ParameterException([
                'msg' => '商品参数不正确'
            ]);
        }

        if (empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach ($values as $value){
            $this->checkProduct($value);
        }

        return true;

    }

    protected function checkProduct($value){
        $validate = new BaseValidate($this->singleRule); //添加验证规则
        $result = $validate ->check($value);
        if (!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误'
            ]);
        }
    }

}