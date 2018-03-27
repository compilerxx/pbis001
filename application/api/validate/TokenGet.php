<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-20
 * Time: 下午9:08
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message =[
        'code' => 'require code to get token.'
    ];


}