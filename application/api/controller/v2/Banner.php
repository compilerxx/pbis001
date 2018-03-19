<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-16
 * Time: 上午8:13
 */

namespace app\api\controller\v2;


class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner 的id 号
     */
    public function getBanner($id){

        return 'this is v2 version!!!';



    }
}