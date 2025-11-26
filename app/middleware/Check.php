<?php

namespace app\middleware;

use think\facade\Cookie;
use app\service\JwtService;

class Check
{
    public function handle($request, \Closure $next,$index = '')
    {

        if(!Cookie::get('Authorization')){
            if($index === 'index') return $next($request);
            return redirect('/');
        }

        $userArray = (new JwtService(config('jwt.key'),config('jwt.expire')))->verifyToken($request,Cookie::get('Authorization'));

        if(!$userArray){
            Cookie::delete('Authorization');
            if($index === 'index') return $next($request);
            return redirect('/');
        }

        $request->user_info = $userArray;

        if($index === 'index') return redirect('admin/index');
        return $next($request);
    }
}