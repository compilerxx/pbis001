<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-19
 * Time: 上午1:01
 */

namespace app\api\controller\v1;


use app\api\model\CategoryModel;
use app\lib\exception\CategoryException;

class Category
{

    /**
     * @return false|static[]
     * @throws CategoryException
     * @url   bis.com/api/v1/category/all
     */

    public function getAllCategories(){
        $categories = CategoryModel::all([],'img');
        //$categories = CategoryModel::with('img')->select(); //此2种相同效果
        if ($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }

}