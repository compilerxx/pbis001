<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-23
 * Time: 下午10:50
 */

namespace app\api\model;


class UserAddressModel extends BaseModel
{
    protected $table = 'user_address';
    protected $pk = 'id';

    protected $hidden =['id','delete_time','user_id'];
//{"name":"Jason Yuan","mobile":"15112600442","province":"GuangDong","city":"GZ","country":"China","detail":"Beauty Soup"}

}