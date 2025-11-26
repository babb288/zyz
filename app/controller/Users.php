<?php

namespace app\controller;

use app\Request;

class Users
{


    public function update(Request $request): \think\response\Json
    {
        $user_info = $request->user_info;

        $id = $request->param('id');
        

        
        $condition = ['id'=>$id,'status'=>0];
        
        $data = $request->param();

        if($user_info['type'] == 'administrator'){
            $condition['upeer'] =$user_info['username'];
        }else if($user_info['type'] != 'super' ){
            $data = $request->except(['remark', 'status']);
        }else{
            unset($condition['status']);
        }

        $data['update_time'] = time();


        $result = \app\model\Users::where($condition)->update($data);

        if($result == 0){
            return json(array('code' => -1,'msg'=>'当前更新未成功'));
        }
        return json(array('code' => 1,'msg' => '更新成功'));
    }

    public function add(Request $request): \think\response\Json
    {

        $phone = $request->param('phone');

        $tiktok =  $request->param('tiktok');

        if(!$phone){
            return json(['code'=>-1,'msg'=>'提交数据有误']);
        }

        $find_phone_result= \app\model\Users::where(['phone'=>$phone])->find();

        if ($find_phone_result){
            return json(['code'=>-1,'msg'=>'手机号已存在']);
        }

        $find_tiktok_result = \app\model\Users::where(['tiktok'=>$tiktok])->find();


        if ($find_tiktok_result){
            return json(['code'=>-1,'msg'=>'tiktok号码已存在']);
        }


        $push_url = $request->param('push_url');

        if(!$push_url){
            return json(array('code'=>-1,'msg'=>'推送连接必须填写'));
        }

        $data['status'] = 0;

        $data = $request->param();

        $user_info = $request->user_info;

        if($user_info['type'] == 'business'){
            $data['upeer'] = $request->user_info['upeer'];
        }
        
        if($user_info['type'] == 'administrator'){
            return json(array(
                'code' => -1,
                'msg'  => '组长不能添加数据'
            ));
        }

        if($user_info['type'] == 'administrator'){
            $data['upeer'] = $request->user_info['username'];
        }

        $data['operator'] = $request->user_info['username'];

        \app\model\Users::create($data);

        return json(['code'=>1,'msg'=>'添加成功']);

    }


    public function delete(Request $request): \think\response\Json
    {
        $id = $request->param('id');

        if($request->user_info['type'] == 'business'){
            return json(array('code' => -1,'msg'=>'您无权限'));
        }

        \app\model\Users::where('id',$id)->delete();

        return json(array('code' => 1,'msg'=>'删除成功'));
    }

    public function list(Request $request): \think\response\Json
    {

        if($request->param('limit') == Null || $request->param('page') == Null || $request->param('limit')  <= 0 || $request->param('page') <= 0 ){
            return json(['code'=>-1,'msg'=>'提交数据错误']);
        }

        $searchParams = $request->param('searchParams');
        
        $condition =  json_decode($searchParams,true);
        $date_condition = [];
        
        
        if($searchParams != null && count($condition) == 1  && (isset($condition['tiktok']) || isset($condition['phone']) )){
            

        }else{
            if($request->user_info['type'] == 'business'){
                $condition['operator'] = $request->user_info['username'];
                $condition['upeer'] = $request->user_info['upeer'];
            }

            if($request->user_info['type'] == 'administrator'){
                $condition['upeer'] = $request->user_info['username'];
            }
            
            
            if(isset($condition['start_date']) && $condition['start_date']){
                $date_condition[] = ['create_time','>=',strtotime($condition['start_date'])];
                unset($condition['start_date']);
            }

            if(isset($condition['end_date']) && $condition['end_date']){
                $date_condition[] = ['create_time','<=',strtotime($condition['end_date'])];
                unset($condition['end_date']);
            }
        }
        
        $phone_condition = [];
        if(isset($condition) && isset($condition['phone'])){
            $phone_condition = [['phone','like', '%'.$condition['phone'] ]];
            unset($condition['phone']);
        }
        


        
        $result =  \app\model\Users::where($condition)->where($date_condition)->where($phone_condition)->order('id','desc')->paginate([
            'list_rows'=>$request->param('limit'),
            'page'=>$request->param('page')
        ]);

        return json([
            'code' => 0,
            'count'=>$result->total(),
            'data' =>$result->items(),
        ]) ;
    }


}