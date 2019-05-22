<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exam\LegalModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class LegalController extends Controller
{
    //注册
    public function register()
    {
        return view('exam.leReg');
    }
    public function regDo(Request $request)
    {
        $le_name = $request->input('le_name');
        $le_person = $request->input('le_person');
        $le_address = $request->input('le_address');
        $le_type = $request->input('le_type');
        $le_img = $request->file('le_img');  //接收图片
        //保存图片
        $img_path = '/public/leImgs';
        $file_name = Str::random(10).'.'.$le_img->getClientOriginalExtension();
        $save_file = $le_img->storeAs($img_path,$file_name);

        $data = [
            'le_name' => $le_name,
            'le_person' => $le_person,
            'le_address' => $le_address,
            'le_type' => $le_type,
            'le_regnum' => '180900'.rand(11111,99999),
            'le_img' => $save_file,
            'le_date' => time(),
            'uid' => Auth::id()
        ];
        $id = LegalModel::insertGetId($data);
        if($id){
            header('Refresh:2;url=/legal/reg/');
            echo '注册成功，正在审核中';
        }else{
            //重新注册
            header('Refresh:2;url=/legal/reg/');
            echo '注册失败';
        }

    }

    //审核通过 生成appid 和 key
    public function checkStatus()
    {
        //$url = 'http://client.1809a.com/legal/checkStatus?regnum=18090011111';
        $le_regnum = $_GET['regnum'];
        $le = $re = LegalModel::where(['le_regnum'=>$le_regnum])->first();
        if($le){
            if($le->le_status == 2){
                $upDate = [
                    'le_appid' => $this->getAppId(),
                    'le_key' => $this->getKey(),
                    'le_status' => 1
                ];
                $re = LegalModel::where(['le_regnum'=>$le_regnum])->update($upDate);
                if($re){
                    $response = [
                        'errcode' => 0,
                        'errmsg' => '快速审核已通过'
                    ];
                }else{
                    $response = [
                        'errcode' => 3002,
                        'errmsg' => '审核通过失败'
                    ];
                }
            }else if($le->le_status == 1){
                $response = [
                    'errcode' => 0,
                    'errmsg' => '您的审核已通过'
                ];
            }
        }else{
            $response = [
                'errcode' => 3003,
                'errmsg' => '注册号不存在'
            ];
        }
        return json_encode($response,JSON_UNESCAPED_UNICODE);
    }

    //请求token
    public function token()
    {
        //$url = 'http://client.1809a.com/legal/token?appid=123456&appkey=67890';
        $appid = $_GET['appid'];
        $appkey = $_GET['appkey'];
        if(empty($appid) || empty($appkey)){
            $response = [
                'errcode' => 3001,
                'errmsg' => '参数不完整'
            ];
        }else{
            $where = [
                'le_appid' => $appid,
                'le_key' => $appkey
            ];
            $re = LegalModel::where($where)->first();
            if($re){
                $access_token = $this->getAccessToken();
                //redis 缓存access_token
                $key = 'access_token:id:'.$re->id;
                $token = Redis::get($key);
                if(empty($token)){
                    Redis::set($key,$access_token);
                    Redis::expire($key,3600);
                }else{
                    $access_token = $token;
                }

                $response = [
                    'errcode' => 0,
                    'errmsg' => 'success',
                    'data' => [
                        'access_token' => $access_token
                    ]
                ];
            }else{
                $response = [
                    'errcode' => 3002,
                    'errmsg' => '参数错误'
                ];
            }
        }
        return json_encode($response,JSON_UNESCAPED_UNICODE);
    }

    //接口 显示客户端ip
    public function getUserIp()
    {
        //$url = 'http://client.1809a.com/legal/getUserIp?token=fdsfdsafdsfds&regnum=18090011111&request=IP';
        $token = $_GET['token'];
        $regnum = $_GET['regnum'];
        $ip = $_GET['request'];
        if($token && $regnum && $ip =='IP'){
            $res = LegalModel::where(['le_regnum'=>$regnum])->first();
            if($res){
                $key = 'access_token:id:'.$res->id;
                $access_token = Redis::get($key);
                if($token == $access_token){
                    $response = [
                        'errcode' => 0,
                        'errmsg' => 'success',
                        'data' => [
                            'ip' => $_SERVER['SERVER_ADDR']
                        ]
                    ];
                }else{
                    $response = [
                        'errcode' => 3007,
                        'errmsg' => 'token错误'
                    ];
                }
            }else{
                $response = [
                    'errcode' => 3006,
                    'errmsg' => '参数错误'
                ];
            }
        }else{
            $response = [
                'errcode' => 3005,
                'errmsg' => '参数错误'
            ];
        }
        return json_encode($response);
    }

    //接口 显示User-Agent
    public function getUA()
    {
        //$url = 'http://client.1809a.com/legal/getUA?token=fdsfdsafdsfds&regnum=18090011111&request=UA';
        $token = $_GET['token'];
        $regnum = $_GET['regnum'];
        $UA = $_GET['request'];
        if($token && $regnum && $UA == 'UA'){
            $res = LegalModel::where(['le_regnum'=>$regnum])->first();
            if($res){
                $key = 'access_token:id:'.$res->id;
                $access_token = Redis::get($key);
                if($token == $access_token){
                    $response = [
                        'errcode' => 0,
                        'errmsg' => 'success',
                        'data' => [
                            'ip' => $_SERVER['HTTP_USER_AGENT']
                        ]
                    ];
                }else{
                    $response = [
                        'errcode' => 3008,
                        'errmsg' => 'token错误'
                    ];
                }
            }else{
                $response = [
                    'errcode' => 3009,
                    'errmsg' => '参数错误'
                ];
            }
        }else{
            $response = [
                'errcode' => 3010,
                'errmsg' => '参数错误'
            ];
        }
        return json_encode($response);

    }

    //接口 显示用户信息
    public function getUserInfo()
    {
        //$url = 'http://client.1809a.com/legal/getUserInfo?token=13treregf&regnum=18090011111&request=userInfo';
        $token = $_GET['token'];
        $regnum = $_GET['regnum'];
        $user = $_GET['request'];
        if($token && $regnum && $user == 'userInfo'){
            $res = LegalModel::where(['le_regnum'=>$regnum])->first();
            if($res){
                $key = 'access_token:id:'.$res->id;
                $access_token = Redis::get($key);
                if($token == $access_token){
                    $response = [
                        'errcode' => 0,
                        'errmsg' => 'success',
                        'data' => [
                            'data' => $res->toArray()
                        ]
                    ];
                }else{
                    $response = [
                        'errcode' => 3011,
                        'errmsg' => 'token错误'
                    ];
                }
            }else{
                $response = [
                    'errcode' => 3012,
                    'errmsg' => '参数错误'
                ];
            }
        }else{
            $response = [
                'errcode' => 3013,
                'errmsg' => '参数错误'
            ];
        }
        return json_encode($response,JSON_UNESCAPED_UNICODE);
    }

    //生成access_token
    public function getAccessToken()
    {
        return sha1(Str::random(20).time());
    }
    //生成appid
    public function getAppId()
    {
        return substr(sha1('1809a'.Str::random(10).'appid'),0,20);
    }
    //生成key
    public function getKey()
    {
        return substr(sha1('1809a'.Str::random(10).'appkey'),0,15);
    }

}
