<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings']
], function($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store');
        // 用户注册
        $api->post('users', 'UsersController@store');
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store');
        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore');
        // 用户登录
        $api->post('authorizations', 'AuthorizationsController@store');
        // 刷新 token
        $api->put('authorizations/current', 'AuthorizationsController@update');
        // 删除 token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 游客可以访问的接口
        $api->get('categories', 'CategoriesController@index');
        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me');
            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update');
            // 图片资源
            $api->post('images', 'ImagesController@store');
            // 发布话题
            $api->post('topics', 'TopicsController@store');
            // 修改话题
            $api->patch('topics/{topic}', 'TopicsController@update');
            // 删除话题
            $api->delete('topics/{topic}', 'TopicsController@destroy');
        });
    });
});
