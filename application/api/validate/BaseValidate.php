<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-17
 * Time: 上午12:00
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获得http传入的参数，而不需要通过函数参数传递
        //对这些参数校验
        $params = Request::instance()->param();
        $check_result = $this->batch()->check($params);

        if (!$check_result){
//            $error = $this->error;
//            throw new Exception($error);

//            $e = new ParameterException();
//            $e->msg = $this->error; //the erro msg from BaseValidate, eg.'id必须是正整数'
            $e = new ParameterException([
                'msg'=> $this->error
            ]);
            throw $e;   // ExceptionHandler will catch the exception and handle
        }else{
            return true;
        }
    }

    protected function isPositiveInteger($value,$rule='',$data='',$field='')
        //tp5 框架要求的参数，如果函数体里没用到该参数，最好给给初始值
        //自定义的验证规则，参数列表要按照tp5框架规定的
    {
        if (is_numeric($value) && is_int($value +0) && ($value +0)> 0){
            return true;
        }else{
            return false;
            //return $field.'必须是正整数';
        }
    }

}