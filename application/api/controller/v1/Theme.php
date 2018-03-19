<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-18
 * Time: 下午6:54
 */

namespace app\api\controller\v1;


use app\api\model\ThemeModel;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * @param string $ids
     * @url /theme?ids=id1,id2,...   http://bis.com/api/v1/theme?ids=1,2,3
     * @throws ThemeException
     * @return  返回一组theme模型
     */
    public function getSimpleList($ids=''){

        (new IDCollection())->goCheck();

        $ids = explode(',',$ids);
        $theme = ThemeModel::with('topicImg','headImg')->select($ids);

        if ($theme->isEmpty()){
        //if (!$theme){ //bug ? 使用select($ids) 判断不了 !$theme 为空？
        // 结果集用isEmpty() 判断，单条记录集用！判断。
            throw new ThemeException();
        }
        return $theme;
    }

    /**
     * @param $id
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws ThemeException
     * @url http://bis.com/api/v1/theme/1
     */
    public function getComplexOne($id){
        (new IDMustBePositiveInt()) ->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if (!$theme){  // 结果集用isEmpty() 判断，单条记录集用！判断。
            throw new ThemeException();
        }
        return $theme;
    }

}