<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-20
 * Time: 下午9:04
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;

class Token
{
    /**
     * @param string $code
     * @return array
     * @url http://bis.com/api/v1/token/user?code=003TEARi15ytxx09QGRi1EECRi1TEARA
     */
    public function getToken($code=''){
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut -> get();

        return [ //不要直接retun $token 字符串。 可以返回数组形式，tp5框架会默认把数组序列化成json 再return
            'token' => $token
        ];
    }

    public function verifyToken($token=''){
        if (!$token){
            throw new ParameterException(['token 不能为空']);
        }
        $valid = TokenService::verifyToken($token);
        return [
          'isValid' => $valid
        ];
    }

}