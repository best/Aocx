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
/* 启用强制路由增加安全性 */

use think\facade\Route;

// 首页
Route::get('/', 'index/index/index');

// 题库
Route::group('practice', function () {
    Route::get('', 'practice/index/index');
    Route::get(':category', 'practice/category/index');
    Route::get(':category/:pID', 'practice/problem/index');
    Route::post('judge', 'practice/judge/index');
});

// 竞赛
Route::group('ctf', function () {
    Route::get('', 'ctf/index/index');
    Route::get(':contest_nickname', 'ctf/contest/index');
    Route::get(':contest_nickname/rule', 'ctf/rule/index');
    Route::get(':contest_nickname/info', 'ctf/info/index');
    Route::get(':contest_nickname/notice', 'ctf/notice/index');
    Route::get(':contest_nickname/rank', 'ctf/rank/index');
    Route::get(':contest_nickname/help', 'ctf/help/index');
//    Route::get(':contest_nickname/:category', 'ctf/category/index');
    Route::get(':contest_nickname/:category/:pID', 'ctf/problem/index');
    Route::post('judge', 'ctf/judge/index');
});

// 排行榜
Route::get('rank', 'rank/index/index');

// 用户
Route::group('user', function () {
    // 登陆
    Route::group('login', function () {
        Route::get('', 'user/login/index');
        Route::post('postCheck', 'user/login/postCheck');
    });
    // 注册
    Route::group('register', function () {
        Route::get('', 'user/register/index');
        Route::get('getUsername', 'user/register/getUsername');
        Route::get('getEmail', 'user/register/getEmail');
        Route::get('getPassword', 'user/register/getPassword');
        Route::post('postCheck', 'user/register/postCheck');
    });
    // 登出
    Route::get('logout', 'user/logout/index');
    // 找回密码
    Route::group('findpass', function () {
        Route::get('', 'user/findpass/index');
        Route::post('postCheck', 'user/findpass/postCheck');
    });
    // 重置密码
    Route::post('resetpass/postCheck', 'user/resetpass/postCheck');
    // 个人中心
    Route::group('profile', function () {
        Route::get('', 'user/profile/index');
        Route::post('postCheck', 'user/profile/postCheck');
        Route::post('avatarPost', 'user/profile/avatarPost');
    });
    // 账户管理
    Route::group('account', function () {
        Route::get('', 'user/account/index');
        Route::post('postUpdatePwd', 'user/account/postUpdatePwd');
        Route::get('getUsername', 'user/account/getUsername');
        Route::post('postUpdateUsername', 'user/account/postUpdateUsername');
    });
    // 答题记录
    Route::group('history', function () {
        Route::get('', 'user/history/index');
        Route::get('rankList', 'user/history/rankList');
    });
    Route::group('ctf', function () {
        Route::get('', 'user/ctf/index');
        Route::get('cancelContest', 'user/ctf/cancelContest');
    });
    // 竞赛报名
    Route::group('apply', function () {
        Route::get('', 'user/apply/index');
        Route::get('contest/:id', 'user/apply/contest');
    });
    Route::get('statistic', 'user/statistic/index');
    // 重发注册验证邮件
    Route::group('mail', function () {
        Route::get('resend', 'user/mail/resend');
        Route::post('resendCheck', 'user/mail/resendCheck');
    });
});

// 注册邮箱验证
Route::get('verify', 'user/mail/registerValidate');

// 密码重置验证
Route::get('passwordReset', 'user/resetpass/index');

// 管理后台
Route::group('manager', function () {
    Route::get('', 'manager/index/index');
    Route::rule('admin/login', 'manager/admin.login/index', 'GET|POST');
    Route::get('admin/logout', 'manager/admin.logout/index');
    Route::group('problem/practice', function () {
        Route::get('', 'manager/problem.practice/index');
        Route::post('add', 'manager/problem.practice/add');
        Route::rule('edit/:pid', 'manager/problem.practice/edit', 'GET|POST');
        Route::post('delete/:pid', 'manager/problem.practice/delete');
    });
    Route::group('problem/contest', function () {
        Route::get('', 'manager/problem.contest/index');
        Route::post('add', 'manager/problem.contest/add');
        Route::rule('edit/:pid', 'manager/problem.contest/edit', 'GET|POST');
        Route::post('delete/:pid', 'manager/problem.contest/delete');
    });
    Route::group('contest', function () {
        Route::get('', 'manager/contest/index');
        Route::post('add', 'manager/contest/add');
        Route::rule('edit/:cid', 'manager/contest/edit', 'GET|POST');
        Route::post('delete/:cid', 'manager/contest/delete');
    });
});

// Miss路由（未匹配到结果的路由将转到这里）
Route::miss(function () {
    return view('../application/common/view/extra/404.html', '', 404);
});