<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 上午8:05
 */

namespace app\api\model;


use think\Model;

class BannerItemModel extends BaseModel
{
    protected $table ='banner_item';
    protected $hidden=['id','img_id','update_time','delete_time'];

    public function img(){
//        return $this->belongsTo('ImageModel','id','img_id');
        return $this->belongsTo('ImageModel','img_id','id');
    }

}