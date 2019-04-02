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
