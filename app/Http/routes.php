<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //前台文章控制器
    Route::get('/','Home\HomeController@index');
    Route::get('list','Home\HomeController@ListFun');
    Route::get('new/{art_id}','Home\HomeController@NewFun');
    //前台會員Login|Logout控制器
    Route::get('login','Home\MemberController@loginPage');
    Route::post('login','Home\MemberController@login');
    Route::get('logout','Home\MemberController@logout');
    Route::post('register','Home\MemberController@register');

    //後台登入控制器
    Route::any('admin/login','Admin\AdminController@index');


    Route::get('test','Home\HomeController@test');
});

Route::group(['middleware' => ['web','auth']], function () {
    //前台會員中心控制器
    Route::get('member-profile','Home\MemberController@member_profile');
    Route::any('member-profile/{id}','Home\MemberController@member_profile_update');
    Route::get('member-add-service','Home\MemberController@member_add_service');
    Route::post('member-add-service','Home\MemberController@mad_action');
    Route::get('member-re-service','Home\MemberController@member_re_service');
    //前台購物車控制器
    Route::resource('cart','Home\CartController');
    Route::resource('order','Home\OrderController');
});

Route::group(['middleware' => ['web','admin.login'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //後台管理者資訊控制器
    Route::get('main-page','AdminController@main_page');
    Route::get('admin-list','AdminController@admin_list');
    Route::get('admin-logout','AdminController@admin_logout');
    Route::any('pass-edit','AdminController@pass_edit');
    //後台文章類別控制器
    Route::resource('article-category-main','CategoryMainController');
    Route::post('article-category-main/create','CategoryMainController@create');
    Route::resource('article-category-sub','CategorySubController');
    //後台文章控制器
    Route::resource('article','ArticleController');
    //後台廣告控制器
    Route::resource('ad-links','LinksController');
    //後台網站配置控制器
    Route::post('configEdit','ConfigController@configEdit');
    Route::get('config/putfile','ConfigController@putFile');
    Route::resource('config','ConfigController');
    //後台會員控制器
    Route::resource('member','MemberController');
    //後台客服控制器
    Route::resource('service-list','ServiceListController');

    //後台Common控制器
    Route::post('upload','CommonController@upload');
    Route::get('storage/{filename}', function ($filename)//利用閉包路由模仿符號鍊結（storage:link in laravel5.3）
    {
        $path = storage_path('uploads/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });
});
