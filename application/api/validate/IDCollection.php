<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 下午7:20
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule=[
        'ids' => 'require|checkIDs'
    ];

    protected $message=[ //$message 也是固定写法
        'ids' => 'ids 参数必须是以逗号分隔的多个正整数'
    ];

    //ids=id1,id2,id3....
    protected function checkIDs($value){

        $values = explode(',',$value);

        if (empty($values)){
            return false;
        }
        foreach($values as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }

        return true;

    }

}