<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 下午6:55
 */

namespace app\api\model;


class ThemeModel extends BaseModel
{
    protected $table = 'theme';
    protected $pk = 'id';
    protected $hidden=['delete_time','update_time','topic_img_id','head_img_id'];
//    protected $hidden=['delete_time','update_time'];

    public function topicImg(){
        return $this->belongsTo('ImageModel','topic_img_id','id');
    }

    public function headImg(){
        return $this->belongsTo('ImageModel','head_img_id','id');
    }

    public function product(){
        return $this->belongsToMany('ProductModel','theme_product',
            'product_id','theme_id');
    }

    public static function getThemeWithProducts($id){
        $theme = self::with('product,topicImg,headImg') -> find($id); //留意with的参数是array或string
        return $theme;
    }
}