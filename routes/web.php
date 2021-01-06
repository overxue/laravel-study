<?php



//  版本格式：主版本号.次版本号.修订号，版本号递增规则如下：

    // 主版本号：当你做了不兼容的 API 修改，
    // 次版本号：当你做了向下兼容的功能性新增，
    // 修订号：当你做了向下兼容的问题修正。
    // 先行版本号及版本编译元数据可以加到“主版本号.次版本号.修订号”的后面，作为延伸。

    // ~表示版本号只能改变最末尾那段（如果是 ~x.y 末尾就是 y，如果是 ~x.y.z 末尾就是 z）
    // ~1.2.3 代表 1.2.3 <= 版本号 < 1.3.0
    // ~1.2   代表  1.2 <= 版本号 <2.0

    // ^表示除了大版本号以外，小版本号和补丁版本号都可以变
    // ^1.2.3 代表 1.2.3 <= 版本号 < 2.0.0
    // ^1.2   代表 1.2 <= 版本号 < 2.0

    // 特殊情况0开头的版本号：
    // ^0.3.0 等于 0.3.0 <= 版本号 <0.4.0  注意：不是 <1.0.0
    // 因为：semantic versioning 的规定是，大版本号以 0 开头表示这是一个非稳定版本（unstable），
    // 如果处于非稳定状态，小版本号是允许不向下兼容的，

    // 所以如果你要指定 0 开头的库那一定要注意：
    // 危险写法：~0.1 等于 0.1.0 <= 版本号 <1.0.0
    // 保险写法：^0.1 等于 0.1.0 <= 版本号 <0.2.0

Route::get('/', 'PagesController@root')->name('root');

// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);


Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');