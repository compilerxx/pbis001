<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-19
 * Time: 上午12:59
 */

namespace app\api\model;


class CategoryModel extends BaseModel
{
    protected $table = 'category';
    protected $pk = 'id';
    protected $hidden=['delete_time','update_time'];

    public function img(){
        return $this->belongsTo('ImageModel','topic_img_id','id');

    }

}