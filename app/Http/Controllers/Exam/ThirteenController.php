<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exam\UserModel;

class ThirteenController extends Controller
{
    //注册
    public function register()
    {
        return view('exam.reg');
    }
    //注册do
    public function regDo()
    {
        $email = \request()->input('email');
        $user_name = \request()->input('user_name');
        $password = \request()->input('password');
        $data = [
            'email' => $email,
            'user_name' => $user_name,
            'password' => $password
        ];
        $json_str = json_encode($data);

        //非对称加密
        $key = openssl_pkey_get_private('file://'.storage_path('app/keys/private.pem'));
        openssl_private_encrypt($json_str,$pass_str,$key);
//        var_dump($pass_str);echo '<hr/>';

        //传输
        $url = 'http://api.1809a.com/login/register/';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$pass_str);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        curl_exec($ch);

        $errno = curl_errno($ch);
        if($errno > 0){
            echo '错误码：'.$errno;die;
        }
        curl_close($ch);


    }
    //登录
    public function login()
    {
        dump(\request()->input());
        echo 111;
    }
}
