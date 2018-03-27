<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-22
 * Time: 上午12:15
 */

namespace app\api\model;


class ProductPropertyModel extends BaseModel
{
    protected $table = 'product_property';
    protected $pk = 'id';
    protected $hidden = ['product_id','delete_time','id'];


}