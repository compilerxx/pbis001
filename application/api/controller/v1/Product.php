<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 下午11:12
 */

namespace app\api\controller\v1;


use app\api\model\ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    /**
     * @param int $count
     * @return array|\PDOStatement|string|\think\Collection
     * @throws ProductException
     * @url   bis.com/api/v1/product/recent?count=2 or bis.com/api/v1/product/recent (15)
     *        bis.com/api/v1/product/by_category?id=3
     */
    public function getRecent($count=15){ //客户端可以选择显示多少个最近新品，默认15个。
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()){   //最好用$products->isEmpty() 来判断空 而不用 !$products
            throw new ProductException();
        }

        $products = $products->hidden(['summary']);
        //需要设置 database.php 里的 'resultset_type'  => 'collection'，接收返回结果为collection 数据集类型，
        //才能用hidden 方法临时隐藏某个field。

        return $products;
    }

    /**
     * @param $id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws ProductException
     * @url   bis.com/api/v1/product/by_category?id=1
     */
    public function getAllInCategory($id){
        (new IDMustBePositiveInt()) ->goCheck();
        $products = ProductModel::getProductsByCategory($id);
        if ($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);

        return $products;
    }

    /**
     * @param $id
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws ProductException
     * @url   bis.com/api/v1/product/2
     */
    public function getOne($id){
        (new IDMustBePositiveInt()) -> goCheck();
        $product = ProductModel::getProductDetail($id);
        if (!$product){
            throw new ProductException();
        }
        return $product;
    }
}