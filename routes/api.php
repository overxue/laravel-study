<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1')->namespace('Api')->name('api.v1.')->group(function() {
    Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
            	// 图片验证码
                Route::post('captchas', 'CaptchasController@store')->name('captchas.store'); 
                // 短信验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')->name('verificationCodes.store');
                // 用户注册
                Route::post('users', 'UsersController@store')->name('users.store');
                // 第三方登录
                Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')->where('social_type', 'wechat')->name('socials.authorizations.store');
                // 登录
                Route::post('authorizations', 'AuthorizationsController@store')
                    ->name('authorizations.store');
                // 刷新 token
                Route::put('authorizations/current', 'AuthorizationsController@update')->name('authorizations.update');
                // 删除 token
                Route::delete('authorizations/current', 'AuthorizationsController@destroy')->name('authorizations.destroy');    
                 
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
            	// 游客可以访问的接口
            	// 话题列表，详情
                Route::resource('topics', 'TopicsController')->only([
                    'index', 'show'
                ]);
            	// 某个用户的详情
            	Route::get('users/{user}', 'UsersController@show')->name('users.show');
            	// 分类列表
            	Route::get('categories', 'CategoriesController@index')->name('categories.index');
            	// 某个用户发布的话题
            	Route::get('users/{user}/topics', 'TopicsController@userIndex')->name('users.topics.index');

            	// 登录后可以访问的接口
                Route::middleware('auth:api')->group(function() {
                    // 当前登录用户信息
                    Route::get('user', 'UsersController@me')->name('user.show');
                    // 编辑用户信息
                    Route::patch('user', 'UsersController@update')->name('user.update');
                    // 上传图片
                    Route::post('images', 'ImagesController@store')->name('images.store');  
                    // 发布话题
                    Route::resource('topics', 'TopicsController')->only(['store', 'update', 'destroy']);
                    // 发布回复
                    Route::post('topics/{topic}/replies', 'RepliesController@store')->name('topics.replies.store');
                   
                });
            });
});


// https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2052d5acb634bcb0&redirect_uri=http://larabbs.test&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect

// 091qjEkl2MVxo648KUnl2HPxwX1qjEkh

// https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx2052d5acb634bcb0&secret=0bcd26d42f1dcd6760d9a9475829af09&code=091qjEkl2MVxo648KUnl2HPxwX1qjEkh&grant_type=authorization_code

// https://api.weixin.qq.com/sns/userinfo?access_token=41_ohdpU-bU55u2HB9S73jC7uIJyUd7kqFjK0K50o7W2WGFp3r-dxbunuK1WAoN3jdyYJxhDB8CXJNCs5MS9qwP1A&openid=odTyHwR3WqZlE-nA2pKGM6DCsD_c&lang=zh_CN
