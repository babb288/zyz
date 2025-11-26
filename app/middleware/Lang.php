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

        if(!$lang && $request->param('lang') != null && in_array($request->param('lang'), ['en', 'km', 'vi'])){
            Cookie::set('lang',$request->param('lang'));
            $lang = $request->param('lang');
        }

        $response = $next($request);

        if ($response instanceof Html) {

            $html = $response->getContent();
            if($lang == 'en'){
                $html = $this->replaceChineseText($html);
            } elseif($lang == 'km'){
                $html = $this->replaceChineseTextToKhmer($html);
            } elseif($lang == 'vi'){
                $html = $this->replaceChineseTextToVietnamese($html);
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
            } elseif($lang == 'km'){
                $data = $this->replaceChineseTextInJsonToKhmer($data);
            } elseif($lang == 'vi'){
                $data = $this->replaceChineseTextInJsonToVietnamese($data);
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
            '推送链接'=>'Push link',
            '推送连接必须填写'=>'Push connection must be filled in',
            '请输入推送连接URL'=>'Please enter the push connection URL',
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
                    '当前更新未成功'        =>  'The current update was unsuccessful',
                    '推送连接必须填写'      =>  'Push connection must be filled in'
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

    private function replaceChineseTextToKhmer($html)
    {
        $translations = [
            '提示' => 'ការជូនដំណឹង',
            '确定要删除吗' => 'តើអ្នកប្រាកដថាចង់លុបឬ?',
            '无权限访问' => 'គ្មានការអនុញ្ញាតចូលប្រើ',
            '文件不能超过' => 'ឯកសារមិនអាចលើស',
            '图片上传成功' => 'ផ្ទុករូបភាពដោយជោគជ័យ',
            '会员管理' => 'ការគ្រប់គ្រងសមាជិក',
            '后台管理' => 'ការគ្រប់គ្រងផ្នែកខាងក្រោយ',
            '退出登录' => 'ចេញពីការចូល',
            '用户名' => 'ឈ្មោះអ្នកប្រើ',
            '密码' => 'ពាក្យសម្ងាត់',
            '状态' => 'ស្ថានភាព',
            '类型' => 'ប្រភេទ',
            '账号' => 'គណនី',
            '备注信息' => 'ព័ត៌មានចំណាំ',
            '备注' => 'ចំណាំ',
            '创建时间' => 'ពេលវេលាបង្កើត',
            '操作人' => 'ប្រតិបត្តិករ',
            '操作' => 'ប្រតិបត្តិការ',
            '手动编辑' => 'កែសម្រួលដោយដៃ',
            '业务员' => 'អ្នកលក់',
            '管理员' => 'អ្នកគ្រប់គ្រង',
            '正常' => 'ធម្មតា',
            '停用' => 'បិទ',
            '留空则为不更改' => 'ទុកទទេដើម្បីមិនផ្លាស់ប្តូរ',
            '开启' => 'បើក',
            '关闭' => 'បិទ',
            '用户添加' => 'បន្ថែមអ្នកប្រើ',
            '请输入' => 'សូមបញ្ចូល',
            '确定' => 'ប្រាកដ',
            '取消' => 'បោះបង់',
            '请选择' => 'សូមជ្រើសរើស',
            '搜 索' => 'ស្វែងរក',
            '会员添加' => 'បន្ថែមសមាជិក',
            '手机号' => 'លេខទូរសព្ទ',
            '真实姓名' => 'ឈ្មោះពិត',
            '性别' => 'ភេទ',
            '未知' => 'មិនស្គាល់',
            '男' => 'ប្រុស',
            '女' => 'ស្រី',
            '年龄' => 'អាយុ',
            '国家' => 'ប្រទេស',
            '地区' => 'តំបន់',
            '职业' => 'មុខរបរ',
            '聊天软件' => 'កម្មវិធីជជែក',
            '婚姻状况' => 'ស្ថានភាពអាពាហ៍ពិពាហ៍',
            '来源' => 'ប្រភព',
            '标签' => 'ស្លាក',
            '未使用' => 'មិនបានប្រើ',
            '已使用' => 'បានប្រើ',
            '普通客户' => 'អតិថិជនធម្មតា',
            '潜在客户' => 'អតិថិជនសក្តានុពល',
            '重要客户' => 'អតិថិជនសំខាន់',
            '成功' => 'ជោគជ័យ',
            '立即登录' => 'ចូលឥឡូវនេះ',
            '登录' => 'ចូល',
            '用户名或密码不正确' => 'ឈ្មោះអ្នកប្រើឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ',
            '编辑人' => 'អ្នកកែសម្រួល',
            '操作人不一致' => 'ប្រតិបត្តិករមិនស្របគ្នា',
            '编辑人不一致' => 'អ្នកកែសម្រួលមិនស្របគ្នា',
            '组长' => 'អ្នកដឹកនាំក្រុម',
            '请输入推送链接' => 'សូមបញ្ចូលតំណភ្ជាប់',
            '推送连接' => 'តំណភ្ជាប់',
            '推送链接' => 'តំណភ្ជាប់',
            '推送连接必须填写' => 'តំណភ្ជាប់ត្រូវតែបំពេញ',
            '请输入推送连接URL' => 'សូមបញ្ចូល URL តំណភ្ជាប់',
            '上传图片（最多9张）' => 'ផ្ទុករូបភាព (ច្រើនបំផុត 9)',
            '上传图片' => 'ផ្ទុករូបភាព',
            '选择图片' => 'ជ្រើសរូបភាព',
            '添加图片' => 'បន្ថែមរូបភាព',
            '已上传' => 'បានផ្ទុក',
            '张图片' => 'រូបភាព',
            '时间范围' => 'ជួរពេលវេលា',
            '开始时间' => 'ពេលវេលាចាប់ផ្តើម',
            '结束时间' => 'ពេលវេលាបញ្ចប់',
            '图片' => 'រូបភាព',
            '删除' => 'លុប',
        ];

        foreach ($translations as $chinese => $translated) {
            $html = str_replace($chinese, $translated, $html);
        }

        return $html;
    }

    private function replaceChineseTextToVietnamese($html)
    {
        $translations = [
            '提示' => 'Thông báo',
            '确定要删除吗' => 'Bạn có chắc chắn muốn xóa không?',
            '无权限访问' => 'Không có quyền truy cập',
            '文件不能超过' => 'Tệp không thể vượt quá',
            '图片上传成功' => 'Tải ảnh lên thành công',
            '会员管理' => 'Quản lý thành viên',
            '后台管理' => 'Quản trị viên',
            '退出登录' => 'Đăng xuất',
            '用户名' => 'Tên người dùng',
            '密码' => 'Mật khẩu',
            '状态' => 'Trạng thái',
            '类型' => 'Loại',
            '账号' => 'Tài khoản',
            '备注信息' => 'Thông tin ghi chú',
            '备注' => 'Ghi chú',
            '创建时间' => 'Thời gian tạo',
            '操作人' => 'Người thực hiện',
            '操作' => 'Thao tác',
            '手动编辑' => 'Chỉnh sửa thủ công',
            '业务员' => 'Nhân viên kinh doanh',
            '管理员' => 'Quản trị viên',
            '正常' => 'Bình thường',
            '停用' => 'Tắt',
            '留空则为不更改' => 'Để trống để không thay đổi',
            '开启' => 'Bật',
            '关闭' => 'Tắt',
            '用户添加' => 'Thêm người dùng',
            '请输入' => 'Vui lòng nhập',
            '确定' => 'Xác nhận',
            '取消' => 'Hủy',
            '请选择' => 'Vui lòng chọn',
            '搜 索' => 'Tìm kiếm',
            '会员添加' => 'Thêm thành viên',
            '手机号' => 'Số điện thoại',
            '真实姓名' => 'Tên thật',
            '性别' => 'Giới tính',
            '未知' => 'Không xác định',
            '男' => 'Nam',
            '女' => 'Nữ',
            '年龄' => 'Tuổi',
            '国家' => 'Quốc gia',
            '地区' => 'Khu vực',
            '职业' => 'Nghề nghiệp',
            '聊天软件' => 'Phần mềm trò chuyện',
            '婚姻状况' => 'Tình trạng hôn nhân',
            '来源' => 'Nguồn',
            '标签' => 'Nhãn',
            '未使用' => 'Chưa sử dụng',
            '已使用' => 'Đã sử dụng',
            '普通客户' => 'Khách hàng thông thường',
            '潜在客户' => 'Khách hàng tiềm năng',
            '重要客户' => 'Khách hàng quan trọng',
            '成功' => 'Thành công',
            '立即登录' => 'Đăng nhập ngay',
            '登录' => 'Đăng nhập',
            '用户名或密码不正确' => 'Tên người dùng hoặc mật khẩu không đúng',
            '编辑人' => 'Người chỉnh sửa',
            '操作人不一致' => 'Người thực hiện không khớp',
            '编辑人不一致' => 'Người chỉnh sửa không khớp',
            '组长' => 'Trưởng nhóm',
            '请输入推送链接' => 'Vui lòng nhập liên kết đẩy',
            '推送连接' => 'Liên kết đẩy',
            '推送链接' => 'Liên kết đẩy',
            '推送连接必须填写' => 'Liên kết đẩy phải được điền',
            '请输入推送连接URL' => 'Vui lòng nhập URL liên kết đẩy',
            '上传图片（最多9张）' => 'Tải ảnh lên (tối đa 9)',
            '上传图片' => 'Tải ảnh lên',
            '选择图片' => 'Chọn ảnh',
            '添加图片' => 'Thêm ảnh',
            '已上传' => 'Đã tải lên',
            '张图片' => 'ảnh',
            '时间范围' => 'Phạm vi thời gian',
            '开始时间' => 'Thời gian bắt đầu',
            '结束时间' => 'Thời gian kết thúc',
            '图片' => 'Ảnh',
            '删除' => 'Xóa',
        ];

        foreach ($translations as $chinese => $translated) {
            $html = str_replace($chinese, $translated, $html);
        }

        return $html;
    }

    private function replaceChineseTextInJsonToKhmer($data)
    {
        $translations = [
            '组长不能添加数据' => 'អ្នកដឹកនាំក្រុមមិនអាចបន្ថែមទិន្នន័យ',
            'tiktok号码已存在' => 'លេខ TikTok មានរួចហើយ',
            '无权限访问' => 'គ្មានការអនុញ្ញាតចូលប្រើ',
            '无权限' => 'គ្មានការអនុញ្ញាត',
            '会员管理' => 'ការគ្រប់គ្រងសមាជិក',
            '账号管理' => 'ការគ្រប់គ្រងគណនី',
            '用户名或密码不正确' => 'ឈ្មោះអ្នកប្រើឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ',
            '账号已添加' => 'គណនីត្រូវបានបន្ថែម',
            '提交数据有误' => 'ទិន្នន័យដែលបានដាក់ស្នើមានកំហុស',
            '添加成功' => 'បន្ថែមដោយជោគជ័យ',
            '更新成功' => 'ធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ',
            '成功' => 'ជោគជ័យ',
            '失败' => 'បរាជ័យ',
            '您无权限' => 'អ្នកគ្មានការអនុញ្ញាត',
            '操作人不一致' => 'ប្រតិបត្តិករមិនស្របគ្នា',
            '编辑人不一致' => 'អ្នកកែសម្រួលមិនស្របគ្នា',
            '当前更新未成功' => 'ការធ្វើបច្ចុប្បន្នភាពបច្ចុប្បន្នមិនទទួលបានជោគជ័យ',
            '推送连接必须填写' => 'តំណភ្ជាប់ត្រូវតែបំពេញ'
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

    private function replaceChineseTextInJsonToVietnamese($data)
    {
        $translations = [
            '组长不能添加数据' => 'Trưởng nhóm không thể thêm dữ liệu',
            'tiktok号码已存在' => 'Số TikTok đã tồn tại',
            '无权限访问' => 'Không có quyền truy cập',
            '无权限' => 'Không có quyền',
            '会员管理' => 'Quản lý thành viên',
            '账号管理' => 'Quản lý tài khoản',
            '用户名或密码不正确' => 'Tên người dùng hoặc mật khẩu không đúng',
            '账号已添加' => 'Tài khoản đã được thêm',
            '提交数据有误' => 'Dữ liệu gửi lên có lỗi',
            '添加成功' => 'Thêm thành công',
            '更新成功' => 'Cập nhật thành công',
            '成功' => 'Thành công',
            '失败' => 'Thất bại',
            '您无权限' => 'Bạn không có quyền',
            '操作人不一致' => 'Người thực hiện không khớp',
            '编辑人不一致' => 'Người chỉnh sửa không khớp',
            '当前更新未成功' => 'Cập nhật hiện tại không thành công',
            '推送连接必须填写' => 'Liên kết đẩy phải được điền'
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