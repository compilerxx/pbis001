<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午12:42
 */

namespace app\lib\enum;


class ScopeEnum
{
    const User = 16;

    const Super = 32; //数字可以随意定，但Super 一般比User 数值大，权限高。

}