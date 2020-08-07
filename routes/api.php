<?php

use Illuminate\Http\Request;

Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {

        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
            	// 图片验证码
            	Route::post('captchas', 'CaptchasController@store')->name('captchas.store');
                // 短信验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')
                    ->name('verificationCodes.store');
                // 用户注册
                Route::post('users', 'UsersController@store')
                    ->name('users.store');
                // 第三方登录
                Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')->where('socal_type', 'weixin')->name('socials.authorizations.store');    
                // 登录
                Route::post('authoriaztions', 'AuthorizationsController@store')->name('authorizations.store');
                // 刷新 token
                Route::put('authorizations/current', 'AuthorizationsController@update')->name('authorizations.update');
                // 删除 token
                Route::delete('authorizations/current', 'AuthorizationsController@destroy')->name('authorizations.destroy');
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {

            });
    });


