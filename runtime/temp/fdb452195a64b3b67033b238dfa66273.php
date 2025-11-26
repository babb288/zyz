<?php /*a:1:{s:60:"C:\Users\Administrator\Desktop\bei\zyz\view\login\index.html";i:1764173950;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="../lib/layui-v2.6.3/css/layui.css" media="all">
	<link rel="stylesheet" href="../css/login.css" media="all">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div class="main-body">
    <div class="login-main">
        <div class="login-top">
            <span>User </span>
            <span class="bg1"></span>
            <span class="bg2"></span>
        </div>
        <form class="layui-form login-bottom">
            <div class="center">
                <div class="item">
                    <span class="icon icon-2"></span>
                    <input type="text" name="username" lay-verify="required"  placeholder="请输入 登录 账号" maxlength="24"/>
                </div>

                <div class="item">
                    <span class="icon icon-3"></span>
                    <input type="password" name="password" lay-verify="required"  placeholder="请输入密码" maxlength="20">
                    <span class="bind-password icon icon-4"></span>
                </div>

<!--                <div  class="item" >-->
<!--                    <span class="icon icon-1"></span>-->
<!--                    <input type="text" name="code" placeholder="请输入谷歌验证码" maxlength="6">-->
<!--                </div>-->

            </div>
<!--            <div class="tip">-->
<!--                <span class="icon-nocheck"></span>-->
<!--                <span class="login-tip">保持登录</span>-->
<!--                <a href="javascript:" class="forget-password">忘记密码？</a>-->
<!--            </div>-->
            <div class="layui-form-item" style="text-align:center; width:100%;height:100%;margin:0px;">
                <button class="login-btn" lay-submit="" lay-filter="login">立即登录</button>
            </div>
        </form>
    </div>
</div>

<script src="../lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script src="../js/md5.js" charset="utf-8"></script>
<script>
    layui.use(['form','jquery'], function () {
        var $ = layui.jquery,
            form = layui.form,
            layer = layui.layer;

        // 登录过期的时候，跳出ifram框架
        if (top.location != self.location) top.location = self.location;

        $('.bind-password').on('click', function () {
            if ($(this).hasClass('icon-5')) {
                $(this).removeClass('icon-5');
                $("input[name='password']").attr('type', 'password');
            } else {
                console.log('触发')
                $(this).addClass('icon-5');
                $("input[name='password']").attr('type', 'text');
            }
        });

        $('.icon-nocheck').on('click', function () {
            if ($(this).hasClass('icon-check')) {
                $(this).removeClass('icon-check');
            } else {
                $(this).addClass('icon-check');
            }
        });

        // 进行登录操作
        form.on('submit(login)', function (data) {
            data = data.field;
            if (data.username === '') {
                layer.msg('用户名不能为空');
                return false;
            }
            if (data.password === '') {
                layer.msg('密码不能为空');
                return false;
            }
            data.password = hex_md5(data.password);
            $.ajax({
                type:'post',
                url:'/login/user',
                data:data,
                dataType:'json',
                success:function(result){
                    if(result.code===1){
                        layer.msg("登录 成功",function(){
                            window.location = '/admin/index';
                        })
                    }else{
                        layer.msg(result.msg);
                    }

                }
            })
            return false;
        });
    });
</script>
</body>
</html>