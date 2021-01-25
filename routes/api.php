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
                // 短信验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')
                    ->name('verificationCodes.store');
                // 用户注册
                Route::post('users', 'UsersController@store')
                    ->name('users.store');
                // 图片验证码
                Route::post('captchas', 'CaptchasController@store')->name('captchas.store');    
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {

            });
});


// https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2052d5acb634bcb0&redirect_uri=http://larabbs.test&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect

// 091qjEkl2MVxo648KUnl2HPxwX1qjEkh

// https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx2052d5acb634bcb0&secret=0bcd26d42f1dcd6760d9a9475829af09&code=091qjEkl2MVxo648KUnl2HPxwX1qjEkh&grant_type=authorization_code

// https://api.weixin.qq.com/sns/userinfo?access_token=41_ohdpU-bU55u2HB9S73jC7uIJyUd7kqFjK0K50o7W2WGFp3r-dxbunuK1WAoN3jdyYJxhDB8CXJNCs5MS9qwP1A&openid=odTyHwR3WqZlE-nA2pKGM6DCsD_c&lang=zh_CN
