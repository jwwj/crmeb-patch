<?php

use think\facade\Route;
//账号密码登录

//.....................


Route::any('routine/notify', 'wechat.AuthController/notify');//小程序支付回调




//在源文件33行处 新增的代码
Route::any('tencent_sms_notify', 'PublicController/tencent_sms_notify');//腾讯云短信发送回调






//管理员订单操作类
Route::group(function () {
    Route::get('admin/order/statistics', 'admin.StoreOrderController/statistics')->name('adminOrderStatistics');//订单数据统计
    Route::get('admin/order/data', 'admin.StoreOrderController/data')->name('adminOrderData');//订单每月统计数据
    Route::get('admin/order/list', 'admin.StoreOrderController/lst')->name('adminOrderList');//订单列表



//.....................



});