<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-2-4
 * Time: 下午9:04
 */

namespace app\sample\controller;

//http://bis.com/sample/test/hello

//use think\Request;
use think\facade\Request;

class Test
{
    //http://bis.com/hello/123?name=jason
    //http://bis.com/hello/123?name=jason&age=99

    //public function hello($id,$name,$age){
    public function hello(){
        //$id = Request::instance()->param('id');
        $all = Request::instance()->param();
        var_dump($all);
        $test = Request::param('name');
        echo $test;

//        echo $id;
//        echo ' ';
//        echo $name;
//        echo ' ';
//        echo $age;
        //return '\n hello jason xxxxxx';
    }

}