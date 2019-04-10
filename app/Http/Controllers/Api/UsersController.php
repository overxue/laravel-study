<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function store(UserRequest $request, User $user)
    {
        $verifyData = \Cache::get($request->input('verification_key'));

        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->input('verification_code'))) {
            return $this->response->errorUnauthorized('验证码错误');
        }
        $user->name = $request->input('name');
        $user->phone = $verifyData['phone'];
        $user->password = bcrypt($request->input('password'));
        $user->save();
        // 清除验证码缓存
        \Cache::forget($request->input('verification_key'));

        return $this->response->created();
    }
}

//https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbb6037df366718b3&redirect_uri=http://larabbs.test&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
//
//https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxbb6037df366718b3&secret=a8a079bf4a3962a0b1a432e7f983ce2f&code=0715Y7r42hELSP0Zkiq42Gnmr425Y7rF&grant_type=authorization_code
//
//https://api.weixin.qq.com/sns/userinfo?access_token=20_hIE_-UT2vHaymm6KvS0kZSgFwvrFyCKOyCL9MOBZB9aXipRrHsLBVw0K74EIKE5gmhTXmQ_8_KG3lfMVx-jmow&openid=oTxpO1ATe5knxPQDFbs7ElKQo-VY&lang=zh_CN
