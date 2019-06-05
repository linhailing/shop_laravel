<?php
use \Illuminate\Support\Facades\Route;


Route::namespace('Admin')->middleware(['web'])->group(function () {
    Route::get('/login', 'LoginController@index')->name('admin.login');
    Route::get('/logout', 'LoginController@logout')->name('admin.logout');
    Route::post('/login/store', 'LoginController@store')->name('admin.login.store');
});

Route::namespace('Admin')->middleware('AdminMiddleware')->group(function (){
    Route::get('/', 'IndexController@index');
    Route::any('/index/data', 'IndexController@getData');
    Route::get('/welcome', 'WelcomeController@index')->name('admin.welcome');
    Route::get('/icons', 'IconsController@index')->name('admin.icons');
    Route::get('/site', 'SiteController@index')->name('admin.site'); //站点配置
    Route::post('/site/store', 'SiteController@store')->name('admin.site.store'); //站点配置
    //用户管理
    Route::get('/user/list', 'SystemController@userList')->name('admin.user.list');
    Route::any('/user/data', 'SystemController@userData')->name('admin.user.data');
    Route::any('/user/store', 'SystemController@userStore')->name('admin.user.store');
    Route::any('/user/del', 'SystemController@userDel')->name('admin.user.del');
    Route::get('/user/role', 'SystemController@userRole')->name('admin.user.role');
    Route::any('/user/role/store', 'SystemController@userRolesStore')->name('admin.user.role.store');
    Route::any('/user/info', 'SystemController@userInfo')->name('admin.user.info');
    Route::any('/user/password', 'SystemController@userPassword')->name('admin.user.password');
    //角色管理
    Route::get('/role/list', 'SystemController@roleList')->name('admin.role.list');
    Route::any('/role/store', 'SystemController@roleStore')->name('admin.role.store');
    Route::any('/role/del', 'SystemController@roleDel')->name('admin.role.del');
    Route::any('/role/permission', 'SystemController@rolePermission')->name('admin.role.permission');
    Route::any('/role/permission/store', 'SystemController@rolePermissionStore')->name('admin.role.permission.store');
    //权限管理
    Route::get('/permission/list','SystemController@permissions')->name('admin.permission.list');
    Route::any('/permission/store','SystemController@permissionStore')->name('admin.permission.store');
    Route::any('/permission/del','SystemController@permissionDel')->name('admin.permission.del');
    //上传图片
    Route::any('/uploadImage', 'UploadController@uploadImage')->name('admin.uploadImage');
    Route::any('/uploadVideos', 'UploadController@uploadVideos')->name('admin.uploadVideos');
    Route::any('/upload/ueditor', 'UploadController@ueditor')->name('admin.upload.ueditor');
    //缓存管理
    Route::any('/cache/data','CacheController@getData')->name('admin.cache.data');
    Route::any('/cache','CacheController@index')->name('admin.cache');
    Route::get('/cache/logs','CacheController@logs')->name('admin.cache.logs');
    Route::any('/cache/logs/del','CacheController@logDel')->name('admin.cache.logs.del');
    // 品牌管理
    Route::get('/product','ProductController@index')->name('admin.product');
    Route::any('/product/data','ProductController@getData')->name('admin.product.data');
    Route::any('/product/store','ProductController@productStore')->name('admin.product.store');
    Route::any('/product/del','ProductController@productDel')->name('admin.product.del');
    Route::get('/product/category','ProductController@category')->name('admin.product.category');
    Route::any('/product/category/store','ProductController@categoryStore')->name('admin.product.category.store');
    Route::any('/product/category/del','ProductController@categoryDel')->name('admin.product.category.del');
});
