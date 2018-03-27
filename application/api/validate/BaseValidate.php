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

    protected function isMobile($value){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        if (empty($value)){
            return false;
        }else{
            return true;
        }

    }

    //根据验证规则获取传入的参数
    public function getDataByRule($arrays){
        if (array_key_exists('user_id',$arrays)|array_key_exists('uid',$arrays)){
            //不允许包含user_id或uid，防止恶意修改user_address 表的外键 user_id
            throw new ParameterException([
               'msg' => '参数中包含有非法参数名user_id 或 uid'
            ]);
        }

        $newArray = [];

        foreach ( $this->rule as $key => $value) { //$this->rule 是指例如验证器AddressNew 里的 $rule
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

}