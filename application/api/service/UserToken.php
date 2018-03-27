<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-20
 * Time: 下午9:18
 */

namespace app\api\service;


use app\api\model\UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code){
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        //3个占位符填login_url
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get(){
        $result = curl_get($this->wxLoginUrl); //curl_get 定义在common.php 中，通过http 调用微信服务器接口获得session_key 及openID
        $wxResult = json_decode($result,true);//字符串转成数组／json ？与json_encode 对应
        if (empty($wxResult)){
            //echo $this->wxLoginUrl;
            throw new Exception('获取session_key 及openID 时异常，微信内部异常');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if ($loginFail){
                $this->processLoginError($wxResult);

            }else{
               return $this->grantToken($wxResult);
            }
        }

    }

    private function grantToken($wxResult){
        //拿到openid
        //检查数据库，这个openid是否已经存在
        //如果存在则不处理，如果不存在则新增一条user记录
        //生成token，准备缓存数据，写入缓存
        //把token返回到客户端去
        //key: token
        //value: wxResult, uid, scope

        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }

        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveCache($cachedValue);
        return $token;


    }

    private function saveCache($cachedValue){ //写入缓存
        $key = self::generateToken();
        $value = json_encode($cachedValue); //数组转成字符串
        $expire_in = config('setting.token_expire_in'); //自定义缓存失效时间

        $request = cache($key,$value,$expire_in); //使用TP5 助手函数写缓存。可以考虑使用redix 写？
        if (!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;
    }

    private function prepareCachedValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;
//        $cachedValue['scope'] = 15;
        //$cachedValue['scope'] = 16; //数字越大权限越高；
        //$cachedValue['scope'] = 32;
        //scope = 16 代表App用户的权限数值
        //scope = 32 代表CMS（管理员）用户的权限数值

        return $cachedValue;
    }

    private function newUser($openid){ //往数据库插入新记录
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult){    //把异常处理封装成方法是为了日后扩展方便，例如记录日子和发送邮件等。
        throw new WeChatException([
           'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }

}