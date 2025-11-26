<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;
Route::group('',function(){

    Route::rule('/','login/index')->middleware(app\middleware\Check::class,'index');

    Route::rule('login/user','login/user');

    Route::group('admin',function (){
        Route::rule('index','admin/index');
        Route::rule('users_edit','admin/users_edit');

        Route::rule('api/init','api/init');

        Route::rule('users_list','admin/users_list');
        Route::rule('users_add','admin/users_add');

        Route::rule('users/list','users/list');
        Route::rule('users/add','users/add');
        Route::rule('users/update','users/update');

        Route::rule('users/delete','users/delete');


        Route::rule('upload/image','upload/image'); //上传图片

        Route::rule('user/cancel','login/cancel');

        Route::group('',function(){
            Route::rule('accounts_list','admin/accounts_list');
            Route::rule('accounts_add','admin/accounts_add');

            Route::rule('accounts_edit','admin/accounts_edit');
            Route::rule('accounts/list','accounts/list');
            Route::rule('accounts/add','accounts/add');
            Route::rule('accounts/edit','accounts/edit');

        })->middleware(app\middleware\Permissions::class);


    })->middleware(app\middleware\Check::class);


})->middleware(app\middleware\Lang::class);

