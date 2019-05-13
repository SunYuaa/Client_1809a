<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exam\UserModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

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
    public function login(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('password');
        $user_name = $request->input('user_name');

        $user = UserModel::where(['email'=>$email])->first();
        if($user){
            //用户存在   验证密码
            if (password_verify($pass,$user->password)){
                //获取token redis存储
                $token = $this->getLoginToken($user->id);
                $login_token_key = 'login_token:id:'.$user->id;
                Redis::set($login_token_key,$token);
                Redis::expire($login_token_key,7*24*3600);

                //登录成功
                $response = [
                    'errcode' => 0,
                    'errmsg' => 'SUCCESS'
                ];
            }else{
                //密码不正确
                $response = [
                    'errcode' => 22010,
                    'errmsg' => '密码错误'
                ];
            }
        }else{
            //用户不存在
            $response = [
                'errcode' => 22021,
                'errmsg' => '用户不存在'
            ];
        }
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }
    /**
     * 获取loginToken
     * @param $id
     * @return bool|string
     */
    public function getLoginToken($id){
        return substr(sha1($id.time().Str::random(15)),5,20);
    }
}
