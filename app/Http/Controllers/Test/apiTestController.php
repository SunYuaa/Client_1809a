<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class apiTestController extends Controller
{
    //发起接口请求  get
    public function testCurl()
    {
        $url = 'http://may.shop.com/api/getM?id=10';

        //创建一个cURL资源
        $ch = curl_init($url);

        //设置URl和相应的选项
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);

        //抓取url并把它传递给浏览器
        $res = curl_exec($ch);
        //错误码
        $code = curl_errno($ch);
//        var_dump($code);

        //关闭cURL资源，并且释放系统资源
        curl_close($ch);
    }

    //post
    public function curlPost()
    {
        $url = 'http://may.shop.com/api/test';
//        $post_info = [
//            'name' => 'zhangsan',
//            'email' => 'zhangsan@qq.com'
//        ];
        $post_str = 'name=zhangsan&email=zhangsan@qq.com';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        //禁止浏览器输出，使用变量接受
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //发送post请求  默认application/x-www-form-urlencoded
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_str);

        //抓取url并把它传递给浏览器
        $res = curl_exec($ch);
        var_dump($res);
        
    }
    //form-data
    public function curlData()
    {
        $url = 'http://may.shop.com/api/test';
        $post_info = [
            'name' => 'lisi',
            'email' => 'lisi@qq.com'
        ];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        //禁止浏览器输出，使用变量接受
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //发送post请求  默认application/x-www-form-urlencoded
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_info);

        //抓取url并把它传递给浏览器
        $res = curl_exec($ch);
        var_dump($res);
    }
    //raw(字符串文本,接收端使用 file_get_contents("php://input"))
    public function curlRaw()
    {
        $url = 'http://may.shop.com/api/test';
        $post_info = [
            'name' => 'wangwu',
            'email' => 'wangwu@qq.com'
        ];

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        //禁止浏览器输出，使用变量接受
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //发送post请求  默认application/x-www-form-urlencoded
        curl_setopt($ch,CURLOPT_POST,1);

        $post_json = json_encode($post_info);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_json);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);

        //抓取url并把它传递给浏览器
        $res = curl_exec($ch);
        var_dump($res);
    }
}
