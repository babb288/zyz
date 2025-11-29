<?php

namespace app\controller;

use app\Request;

class Accounts
{


    public function list(Request $request): \think\response\Json
    {

        if($request->param('limit') == Null || $request->param('page') == Null || $request->param('limit')  <= 0 || $request->param('page') <= 0 ){
            return json(['code'=>-1,'msg'=>'提交数据错误']);
        }

        $searchParams = $request->param('searchParams');
        $condition =  json_decode($searchParams,true);

        $user_info = $request->user_info;

        if($request->user_info['type'] == 'business'){
            return json(['code'=>-1,'msg'=>'无权限']);
        }

        if($user_info['type'] != 'super'){
            $condition['upeer'] = $user_info['username'];
        }

        $result =  \app\model\Admin::where($condition)->order('id','desc')->paginate([
            'list_rows'=>$request->param('limit'),
            'page'=>$request->param('page')
        ]);

        return json([
            'code' => 0,
            'count'=>$result->total(),
            'data' =>$result->items(),
        ]) ;

    }

    public function add(Request $request): \think\response\Json
    {

        $data = $request->param();

        $user_info = $request->user_info;


//        if($data['type'] == 'business' && $user_info['type'] == 'super')
//        {
//            return json(['code' => -1,'msg' => '超级管理员不能添加业务员']);
//        }

        if($data['type'] == 'administrator' && $user_info['type'] == 'administrator')
        {
            return json(['code' => -1,'msg' => '无权限']);
        }

        $data['upeer'] = $user_info['username'];

        $find_accounts_result = \app\model\Admin::where('username',$data['username'])->find();

        if($find_accounts_result){
            return json(['code'=>-1,'msg'=>'账号已存在']);
        }

        $data['create_time'] = time();

        \app\model\Admin::create($data);

        return json(['code'=>1,'msg'=>'账号已添加']);
    }

    public function edit(Request $request): \think\response\Json
    {


        $data  = $request->param();

        if($data['username'] == 'admin'){
            return json(['code'=>-1,'msg'=>'无权限']);
        }

        if($data['password'] == ""){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }

        if($request->user_info['type'] == 'business'){
            return json(['code'=>-1,'msg'=>'无权限']);
        }

        $condition['username'] = $data['username'];

        if($request->user_info['type'] == 'administrator'){
            $condition['upeer'] = $request->user_info['username'];

        }

        \app\model\Admin::update($data,$condition);

        return json(['code'=>1,'msg'=>'账户已更改']);
    }

}