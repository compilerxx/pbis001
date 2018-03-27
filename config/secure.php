<?php
/**
 * Created by PhpStorm.
 * User: compiler
 * Date: 18-3-21
 * Time: 上午12:18
 */

//自定义的配置file

return [
    'token_salt' => 'HHsTieBU377mJtKr',
    'pay_back_url' => 'http://bis.com/api/v1/pay/notify' //for service/Pay.php -> SetNotify_url, 微信的回调接口url
    //这里是本地的 url 地址，需要把应用部署到公网或云服务器上微信才能访问到。能不能把本机变成服务器让微信也能访问到？
    //把本机变成外网可以访问，可以用软件Ngrok(是一种反向代理，不安全) 把本机服务器变成外网可以访问的服务器，当运行Ngrok它会提供一个新的域名，
    //通过该域名就可以被外网访问。
];