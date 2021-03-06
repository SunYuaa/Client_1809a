<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class RequestNum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $_SERVER['SERVER_ADDR'];
        $key = 'requestNum:ip:'.$ip.':req:'.$request->input('request');
        $num = Redis::get($key);
        Redis::incr($key);
        if($num > 20){
//            Redis::expire($key,1800);
            die('请求次数超过限制');
        }

        echo 'num:'.$num;echo '<br/>';
        echo 'key:'.$key;echo '<hr/>';

        Redis::expire($key,60);

        return $next($request);
    }
}
