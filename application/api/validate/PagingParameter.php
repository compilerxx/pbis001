<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-25
 * Time: 下午11:50
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule=[
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];
    protected $message=[
        'page' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数'
    ];

}