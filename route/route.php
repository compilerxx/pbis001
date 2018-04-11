<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//  /Applications/XAMPP/xamppfiles/etc/extra/httpd-vhosts.conf
/// <VirtualHost *:80>
//   DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/pbis001/public"
//   ServerName bis.com
//  </VirtualHost>
//
// 2,update hosts , /etc/hosts
// 'url_route_must' => true  -> 开启强制路由，PATH_INFO模型 和路由模型


//http://120.79.90.185/pbis001/public/think
//http://120.79.90.185/pbis001/public/hello/sb

//use think\Route;
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

//Route::get('hello/:name', 'index/hello');
//Route::get('hello/:id', 'sample/Test/hello');
//Route::post('hello', 'sample/Test/hello');  -> //bis.com/hello?id=12&name=jason
//Route::get('hello/:id', 'sample/Test/hello'); //=> //bis.com/hello/123?name=jason
//http://bis.com/hello/j
//http://localhost/pbis001/public/hello/j

Route::get('banner/:id','api/v1.Banner/getBanner');
//Route::get('api/v1/banner/:id','api/v1.Banner/getBanner');
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner'); //版本控制
Route::get('api/:version/theme','api/:version.Theme/getSimpleList'); //在url加?ids=1,2,3... 传入参数

Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');
//需要设置路由使用完整匹配：'route_complete_match'   => true，因为当路由到前面一条/theme 就不会路由到/theme/:id

//Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
////Route::get('api/:version/product/:id','api/:version.Product/getOne');
//Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']); //设置id是正整数才进该路由

//路由分组,路由分组效率要比直接写的路由效率要高些。
Route::group('api/:version/product/',function (){
    Route::get('recent','api/:version.Product/getRecent');
    Route::get('by_category','api/:version.Product/getAllInCategory');
    Route::get(':id','api/:version.Product/getOne');
});

Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

Route::post('api/:version/token/user','api/:version.Token/getToken');
//注意这里使用post，通过body传入$code 参数，安全性稍好点
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');

Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');

Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');//需要传一个参数 ?id=xx
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');


return [

];

//定义了路由之后，原有的url 就会实效，不能直接访问