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
}
