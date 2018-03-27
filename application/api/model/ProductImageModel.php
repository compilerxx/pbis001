<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-22
 * Time: 上午12:14
 */

namespace app\api\model;


class ProductImageModel extends BaseModel
{
    protected $table ='product_image';
    protected $pk = 'id';

    protected $hidden=['img_id','delete_time','product_id'];

    public function imgUrl(){
        return $this->belongsTo('ImageModel','img_id','id');
    }

}