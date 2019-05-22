<?php

namespace App\Http\Controllers\Work;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxController extends Controller
{
    //微信第一次连接测试
    public function valid()
    {
        echo $_GET['echostr'];
    }
    //
    public function event()
    {
        //接受服务器推送
        $content = file_get_contents("php://input");
        //写入日志
        $time = date("Y-m-d H:i:s");
        $str = $time . $content . "\n";
        file_put_contents("logs/wx_event.log", $str, FILE_APPEND);

        $data = simplexml_load_string($content);
        echo "ToUserName:".$data->ToUserName;echo '</br>';      //公众号ID
        echo "FromUserName:".$data->FromUserName;echo '</br>';  //用户OpenID
        echo "CreateTime:".$data->CreateTime;echo '</br>';      //时间戳
        echo "MsgType:".$data->MsgType;echo '</br>';            //消息类型
        echo "Event:".$data->Event;echo '</br>';                //事件类型
        echo "Content:".$data->Content;echo '</br>';                //事件类型
        echo "EventKey:".$data->EventKey;echo '</br>';
    }
}
