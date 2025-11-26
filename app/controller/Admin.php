<?php

namespace app\controller;

use app\BaseController;
use app\Request;
use think\facade\View;

class Admin extends BaseController
{

    public function index(): string
    {
        return View::fetch();
    }


    public function users_list(): string
    {
        return View::fetch();
    }
    public function users_add(): string
    {
        return View::fetch();
    }


    public function users_edit(Request $request)
    {

        $user_info = \app\model\Users::where(['id'=>$request->param('id')])->find();
        if(!$user_info){
            return json(['code' => -1,'msg' => '提交数据有误']);
        }
        
        $type = $request->user_info['type'];
        View::assign('type',$type);
        View::assign('user_info',$user_info);
        return View::fetch();
    }


    public function accounts_list(): string
    {
        return View::fetch();
    }

    public function accounts_add(): string
    {
        return View::fetch();
    }


    public function accounts_edit(Request $request)
    {
        $user_info = \app\model\Admin::where(['id'=>$request->param('id')])->find();

        if(!$user_info){
            return json(['code' => -1,'msg' => '提交数据有误']);
        }
        


        
        View::assign('user_info',$user_info);
        return View::fetch();
    }



}