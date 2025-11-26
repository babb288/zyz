<?php
namespace app\controller;

use think\facade\Filesystem;
use think\facade\Request;

class Upload
{
    /**
     * 图片上传（支持多图上传）
     */
    public function image(): \think\response\Json
    {
        // 获取上传的文件对象
        $file = Request::file('file');
        if (!$file) {
            return json(['code' => 1, 'msg' => '未上传文件']);
        }

        try {
            // 保存路径：/public/uploads/2025-05-29/
            $saveName = Filesystem::disk('public')->putFile('uploads/' . date('Y-m-d'), $file);

            // 返回的图片完整访问路径
            $url = request()->domain() . '/storage/' . $saveName;

            return json([
                'code' => 0,
                'msg' => '上传成功',
                'url' => $url
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 1,
                'msg'  => '上传失败：' . $e->getMessage()
            ]);
        }
    }
}
