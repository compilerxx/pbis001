<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-16
 * Time: 上午8:13
 */

namespace app\api\controller\v1;


use app\api\model\BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\TestValidate;
use app\lib\exception\BannerMissException;
use think\Exception;
use think\Validate;

class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id  http://bis.com/api/v1/banner/1
     * http://120.79.90.185/pbis001/public/api/v1/banner/1
     * @http GET
     * @id banner 的id 号
     */
    public function getBanner($id){

        (new IDMustBePositiveInt())->goCheck();

        $banner = BannerModel::getBannerById($id);



        //$banner = BannerModel::all($id);
        //$banner = BannerModel::get($id);
        //$banner = BannerModel::find($id);
        //$banner = BannerModel::get($id,'items');
        //$banner = BannerModel::where('id','=',1)->find($id);
        //$banner = BannerModel::with('items')->find();
        //$banner = BannerModel::with(['items','items.img'])->find($id); //嵌套关联

        if(!$banner){
            throw new BannerMissException();
            //throw new Exception('internal error');
        }

        //$img_path_prefix = config('setting.img_path_prefix');
        //echo $img_path_prefix;
        //return json($banner);
        return $banner;

//        $data = [
//          'name' => 'vendor12345',
//          'email' => 'vendorqq.com'
//        ];

//        $validate = new Validate(
//            [
//                'name' =>'require|max:10',
//                'email' => 'email'
//            ]
//        );

//        $validate = new TestValidate();

//        $data =[
//            'id' => $id
//        ];
//
//        $validate = new IDMustBePositiveInt();
//
//        $result = $validate ->check($data);
//
//        if (!$validate->batch()->check($data)) {
//            dump($validate->getError());
//        }





    }
}