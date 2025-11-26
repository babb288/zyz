<?php

namespace app\controller;

use app\BaseController;
use app\model\Admin;
use app\Request;
use app\service\JwtService;
use think\facade\Cookie;
use think\facade\View;

class Login extends BaseController
{

    public function index(): string
    {
        return view::fetch();
    }


    public function user(Request $request): \think\response\Json
    {
        $data = [
            'username'  => $request->param('username'),
            'password'  => $request->param('password'),
            'status'    => 1
        ];

        $find_user_result = Admin::where($data)->find();

        if(!$find_user_result){
            return json(array('code'=>-1,'msg'=>'用户名或密码不正确'));
        }

        $arr = array(
            'username'  =>  $request->param('username'),
            'ip'        =>  $request->ip(),
            'type'      =>  $find_user_result->type,
        );

        if($find_user_result['type'] == 'business'){
            $arr['upeer'] = $find_user_result->upeer;
        }

        $token =    (new JwtService(config('jwt.key'),config('jwt.expire')))
            ->generateToken($request, $arr);

        Cookie::set('Authorization',$token,config('jwt.expire'));

        return json(array('code' => 1, 'msg' => '登录成功'));

    }

    public function cancel(Request $request): \think\response\Redirect
    {
        Cookie::delete('Authorization');
        return redirect('/');
    }


}