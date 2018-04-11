<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-21
 * Time: 上午12:09
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class Token
{
    public static function generateToken(){
        //32 个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //用三组字符串，进行md5 加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT']; //第二组字符串使用时间戳
        //salt 盐，组secure.php 中配置
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);

    }

    //考虑扩展性，新建一个通用方法
    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        //如何获取用户token？需要一个约定，约定所有用户的token 都是放在http 请求的header 头里面传入而不能放在body里传入。

        $vars = Cache::get($token);
        if (!$vars){
            throw new TokenException();
        }else{
            if (!is_array($vars)){ //如果变量本来就是数组就不用decode了
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key,$vars)){
                // token 下的 key --> value
                //缓存中token的内容：{"session_key":"Qoo\/S4lC+\/VAWn7OWqx4\/A==","expires_in":7200,"openid":"oz_nw0ONjFfQ-yozu916jT-SpTqE","uid":1,"scope":16}
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的token变量不存在');
            }
        }
    }

    public static function getCurrentUid(){
        //token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;

    }

    //允许用户和CMS都可以访问的权限
    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope >= ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }

    }

    //只有用户才能访问的接口权限
    public static function needExclusiveScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope == ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }

    }

    public static function isValidOperate($checkUID){
        if (!$checkUID){
            throw new Exception('检查UID 时必须传入一个要检查的UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if ($currentOperateUID == $checkUID){
            return true;
        }

        return false;

    }

    public static function verifyToken($token){
        $exist = Cache::get($token);
        if ($exist){
            return true;
        }else{
            return false;
        }
    }

}