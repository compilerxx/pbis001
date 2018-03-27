<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-20
 * Time: 下午9:16
 */

namespace app\api\model;


class UserModel extends BaseModel
{
    protected $table = 'user';
    protected $pk = 'id';

    //定义user 和 user_address 之间的关联关系
    //如何区别hasOne 和 belongsTo ？ 如果在有foreignkey的一方定义一对一关系的话则使用belongsTo，反之使用hasOne。
    public function address(){
        return $this->hasOne('UserAddressModel','user_id','id');
    }

    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }


}