<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-23
 * Time: 下午8:40
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\UserModel;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;


class Address extends BaseController  //为了使用tp5的前置方法，需要继承tp5基类 Controller。
{
    protected $beforeActionList = [ //Controller 的成员变量
        'checkPrimaryScope' => ['only'=>'createOrUpdateAddress'] //执行 createOrUpdateAddress 之前先执行前置方法 checkPrimaryScope
    ];

// 该方法已提取到基类 BaseController
//    protected function checkPrimaryScope(){
//        $scope = TokenService::getCurrentTokenVar('scope');
//        if ($scope){
//            if ($scope >= ScopeEnum::User){
//                return true;
//            }else{
//                throw new ForbiddenException();
//            }
//        }else{
//            throw new TokenException();
//        }
//    }



    /**
     * @return SuccessMessage
     * @throws UserException
     * @url   bis.com/api/v1/address
     */
    public function createOrUpdateAddress(){
        //(new AddressNew()) ->goCheck();
        $validate = new AddressNew();
        $validate ->goCheck();

        // 根据token来获取uid
        // 根据uid来查找用户数据，判断用户是否存在，如果不存在则抛出异常
        // 获取用户从客户端提交的地址信息
        // 根据用户地址信息是否存在, 判断是新增地址还是更新地址

        $uid = TokenService::getCurrentUid();
        //return $uid;
        $user = UserModel::get($uid);
        if (!$user){
            throw new UserException();
        }

        $dataArray = $validate->getDataByRule(input('post.')); //input('post.') 获取post 传过来的所有变量

        $userAddress = $user->address; //这里address 是什么？
        if (!$userAddress){
            $user->address()->save($dataArray); //这里address() 是什么？
        }else{
            $user->address->save($dataArray); //注意address和address()的区别。
        }

        return json(new SuccessMessage(),201);
    }

}