<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 下午11:25
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15'
    ];

    protected $message =[
        'count' => 'count 数必须是1-15之间的正整数'
    ];

}