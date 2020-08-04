<?php

use Illuminate\Http\Request;

Route::prefix('v1')->name('api.v1.')->group(function () {
    // 短信验证码
    Route::post('verificationCodes', 'api\VerificationCodesController@store')
        ->name('verificationCodes.store');
});