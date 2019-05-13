<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    //凯撒加密
    public function caser_encypt($text,$n)
    {
        $str_len = strlen($text);
        $pass = '';
        for($i=0;$i<$str_len;$i++){
            $ord_str = ord($text[$i]) + $n;
            $pass .= chr($ord_str);
        }
        return $pass;
    }
    //凯撒解密
    public function decypt($pass,$n)
    {
        $str_len = strlen($pass);
        $a = '';
        for($i=0;$i<$str_len;$i++){
            $str = ord($pass[$i]);
            $a .= chr($str - $n);
        }
        return $a;echo '<br/>';
    }
    //test
    public function test()
    {
        $text = 'hello world';
        $n = 1;

        $pass = $this->caser_encypt($text,$n);
        echo '密文：'.$pass;echo '<br>';
        $first = $this->decypt($pass,$n);
        echo '明文：'.$first;echo '<br>';
    }

    //
    public function en()
    {
        $data = 'hello world';
        $method = 'AES-256-CBC';
        $key = '11223344';
        $options = OPENSSL_RAW_DATA;
        $iv = '1234567891234567';   //必须十六位

        $en_str = openssl_encrypt($data,$method,$key,$options,$iv);
        $last = base64_encode($en_str);
        echo $last;
    }
    //
    public function de()
    {
        $str = $_GET['str'];
        $method = 'AES-256-CBC';
        $key = '11223344';
        $options = OPENSSL_RAW_DATA;
        $iv = '1234567891234567';

        $de_str = base64_decode($str);
        $res = openssl_decrypt($de_str,$method,$key,$options,$iv);
        echo $res;
    }

    //对称加密
    public function sendSec()
    {
        $data = [
            'name' => 'admin',
            'pwd' => 'admin123',
            'sex' => 1,
        ];
        $json_str = json_encode($data);
        //加密
        $method = 'AES-256-CBC';
        $key = 'abcd';
        $options = OPENSSL_RAW_DATA;
        $iv = 'aabbccddeeffgghh';
        $send_str = base64_encode( openssl_encrypt($json_str,$method,$key,$options,$iv) );
        echo $send_str;echo '<hr>';

        //传输地址
        $url = 'http://api.1809a.com/test/reqSec/';
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$send_str);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        $response = curl_exec($ch);

        $code = curl_errno($ch);
        if($code>0){
            echo $code;die;
        }
        curl_close($ch);

    }
    //非对称加密
    public function unSec()
    {
        $data = [
            'goodsname' => 'apple',
            'place' => 8200,
            'address' => '香山'
        ];
        $json_str = json_encode($data,JSON_UNESCAPED_UNICODE);

        //非对称加密
        $key = openssl_pkey_get_private('file://'.storage_path('app/keys/private.pem'));
        openssl_private_encrypt($json_str,$crypted,$key);
        var_dump($crypted);echo '<br>';

        //传输地址
        $url = 'http://api.1809a.com/test/unSec/';
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$crypted);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        $response = curl_exec($ch);

        $code = curl_errno($ch);
        if($code>0){
            echo $code;die;
        }
        curl_close($ch);


    }
    //非对称 签名加密
    public function testSign()
    {
        $data = [
            'name' => 'admin',
            'pwd' => 'admin123',
            'cartId' => 135246123456,
        ];
        $json_str = json_encode($data);
        //非对称签名加密
        $key = openssl_pkey_get_private('file://'.storage_path('app/keys/private.pem'));
        //计算签名  使用私钥对数据加密
        openssl_sign($json_str,$signature,$key);
        $b64 = base64_encode($signature);

        //传输
        $url = 'http://api.1809a.com/test/reqSign?sign='.urlencode($b64);
        echo $url;
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$json_str);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        $response = curl_exec($ch);
//
//        $code = curl_errno($ch);
//        if($code>0){
//            echo $code;die;
//        }
//        curl_close($ch);
    }

}
