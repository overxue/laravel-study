<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;
use Cache;
use App\Notifications\EmailVerificationNotification;
use Mail;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        // 从URL中获取 email 和 token
        $email = $request->input('email');
        $token = $request->input('token');
        // 如果有一个为空抛出异常
        if (!$email && !$token) {
            throw new InvalidRequestException('验证链接不正确');
        }
        if ($token != Cache::get('email_verification_'.$email)) {
            throw new InvalidRequestException('验证链接不正确或以过期');
        }
        if (!$user = User::where('email', $email)->first()) {
            throw new InvalidRequestException('用户不存在');
        }
        // 删除key
        Cache::forget('email_verification_'.$email);
        $user->update(['email_verified' => true]);
        return view('pages.success', ['msg' => '邮箱验证成功']);
    }

    public function send(Request $request)
    {
        $user = $request->user();
        // 判断当前用户是否激活
        if ($user->email_verified) {
            throw new InvalidRequestException('你已验证过邮箱了');
        }
        $user->notify(new EmailVerificationNotification());

        return view('pages.success', ['msg' => '邮件发送成功']);
    }
}
