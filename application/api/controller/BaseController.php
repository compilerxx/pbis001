<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-24
 * Time: 上午1:54
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }

}