<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use Illuminate\Auth\AuthenticationException;
use App\Http\Resources\UserResource;
use App\Models\User;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
    	$verifyData = \Cache::get($request->verification_key);
    	if (!$verifyData) {
    		abort(403, '验证码失效');
    	}

    	if (!hash_equals($verifyData['code'], $request->verification_code)) {
    		throw new AuthenticationException('验证码错误');
    	}

    	$user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            // 'password' => $request->password,
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return new UserResource($user);
    }
}
