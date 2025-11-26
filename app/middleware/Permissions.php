<?php

namespace app\middleware;

class Permissions
{

    public function handle($request, \Closure $next,$index = '')
    {

        if($request->user_info['type'] =='business'){
            return json(array('code' => -1, 'msg' => '无权限访问'));
        }

        return $next($request);
    }

}