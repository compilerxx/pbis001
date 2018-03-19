<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 下午6:54
 */

namespace app\api\model;


class ProductModel extends BaseModel
{
    protected $table = 'product';
    protected $pk = 'id';

    protected $hidden = ['delete_time','main_img_id','pivot','from',
        'category_id','create_time','update_time'];
    //pivot 是 根据 table theme_product 生成的。

    public function getMainImgUrlAttr($value,$data){ // table product field main_img_url field ?

        return $this->concatImaUrl($value,$data); //调用基类方法
    }

    public static function getMostRecent($count){
//        $products = self::limit($count)->order('create_time desc')->select();
        $products = self::limit($count)->order('create_time','desc')->select();
        return $products;
    }

    public static function getProductsByCategory($categoryID){
        $products = self::where('category_id','=',$categoryID)
            ->select();
        return $products;
    }
}