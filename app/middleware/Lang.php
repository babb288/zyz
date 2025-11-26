<?php

namespace app\middleware;

use think\response\Html;
use think\response\Json;
use think\response\Redirect;
use think\facade\Cookie;

class Lang
{

    public function handle($request, \Closure $next,$index = '')
    {
        $lang  = Cookie::get('lang');

        if(!$lang && $request->param('lang') != null && $request->param('lang') == 'en'){
            Cookie::set('lang',$request->param('lang'));
            $lang = $request->param('lang');
        }

        $response = $next($request);

        if ($response instanceof Html) {

            $html = $response->getContent();
            if($lang == 'en'){
                $html = $this->replaceChineseText($html);
            }


            return $response->content($html);  // 返回翻译后的 HTML 响应
        }

        if ($response instanceof Redirect) {
            return $response;
        }

        if ($response instanceof Json) {

            $data = $response->getData();

            if($lang == 'en'){
                $data = $this->replaceChineseTextInJson($data);
            }

            return $response->data($data);  // 返回修改后的 JSON 响应
        }


        return $response;
    }


    private function replaceChineseText($html)
    {
        $translations = [
            
            '提示' =>'prompt',
            '确定要删除吗' =>'Are you sure you want to delete it',
            '无权限访问'=>'No access permission',
            '文件不能超过' => 'The file cannot exceed',
            '图片上传成功' => 'Picture uploaded successfully',
            '会员管理' => 'member manage',
            '后台管理' =>   'Backstage Management',
            '退出登录' =>   'Log out',
            '用户名'  =>  'username',
            '密码'    => 'password',
            '状态'    =>  'status',
            '类型'    => 'type',
            '账号'    => 'account',
            '备注信息' =>'remark info',
            '备注'    => 'remark',
            '创建时间' => 'Creation time',
            '操作人'   =>'Operator',
            '操作'    => 'operate',
            '手动编辑'=>'Manual Editing',
            '业务员' => 'Salesman',
            '管理员' => 'administrator',
            '正常'  => 'normal',
            '停用' => 'Deactivate',
            '留空则为不更改' => 'Leave blank to make no changes',
            '开启' => 'Open',
            '关闭'        => 'close',
            '用户添加'     => 'User Add',
            '请输入'      =>'Please enter',
            '确定'        => 'Sure',
            '取消'        => 'Cancel',
            '请选择'       => 'Please select',
            '搜 索'         => 'search',
            '会员添加'      => 'Add Member',
            '手机号'           =>'Phone',
            '真实姓名'       => 'Real name',
            '性别'        =>'sex',
            '未知'        =>'unknown',
            '男'       => 'male',
            '女'      =>'female',
            '年龄' =>'age',
            '国家' =>'country',
            '地区' => 'area',
            '职业' => 'Profession',
            '聊天软件' => 'Chat software',
            '婚姻状况'=>'Marital status',
            '来源' =>'source',
            '标签' =>'Label',
            '未使用' =>'Available',
            '已使用' =>'Idle',
            '普通客户' => 'Regular customers',
            '潜在客户' =>'Potential Customers',
            '重要客户' =>'Important Customers',
            '成功' =>'success',
            '立即登录' => 'Sign in now',
            '登录' => 'login',
            '用户名或密码不正确' => 'Incorrect username or password',
            '编辑人'=>'Editor',
            '操作人不一致'=>'Inconsistent operators',
            '编辑人不一致'=>'Inconsistent editors',
            '组长' => 'team leader',
            '请输入推送链接'=>'Please enter the push link',
            '推送连接'=>'Push connection',
            '上传图片（最多9张）' => 'Upload images (up to 9)',
            '上传图片' => 'Upload image',
            '选择图片' => 'Select image',
            '添加图片' => 'Add image',
            '已上传' =>'Uploaded',
            '张图片' => 'a picture',
            '时间范围' => 'time range',
            '开始时间' => 'Start time',
            '结束时间' => 'End time',
            '图片'=>'image',
            '删除' => 'delete',
        ];

        foreach ($translations as $chinese => $translated) {
            $html = str_replace($chinese, $translated, $html);
        }

        return $html;
    }

    private function replaceChineseTextInJson($data)
    {
        $translations = [
                    '组长不能添加数据'      =>  'The team leader cannot add data',
                    'tiktok号码已存在'      =>  'TikTok number already exists',
                    '无权限访问'            =>  'No access permission',
                    '无权限'                =>  'No permission',
                    '会员管理'              =>  'member manage',
                    '账号管理'              =>  'Account Management',
                    '用户名或密码不正确'    =>  'Incorrect username or password',
                    '账号已添加'            =>  'Account added',
                    '提交数据有误'          =>  'Incorrect submitted data',
                    '添加成功'              =>  'Added successfully',
                    '更新成功'              =>  'Update Success',
                    '成功'                  =>  'success',
                    '失败'                  =>  'error',
                    '您无权限'              =>  'You do not have permission',
                    '操作人不一致'          =>  'Inconsistent operators',
                    '编辑人不一致'          =>  'Inconsistent editors',
                    '当前更新未成功'        =>  'The current update was unsuccessful'
        ];

        array_walk_recursive($data, function (&$value) use ($translations) {
            if (is_string($value)) {
                foreach ($translations as $chinese => $translated) {
                    $value = str_replace($chinese, $translated, $value);
                }
            }
        });

        return $data;
    }

}