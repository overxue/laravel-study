<?php

namespace App\Observers;

use App\Models\User;
use Avatar;
use Overtrue\Pinyin\Pinyin;
use Illuminate\Support\Facades\Storage;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public function saving(User $user)
    {
        if (empty($user->avatar)) {
            $user_name = app(Pinyin::class)->abbr($user->name);
            $filename = \sprintf('avatars/%s.png', random_int(0, 999));
            $filepath = \storage_path('app/public/'.$filename);

            if (!\is_dir(\dirname($filepath))) {
                \mkdir(\dirname($filepath), 0755, true);
            }
            Avatar::create($user_name)->save(Storage::disk('public')->path($filename));
            $user->avatar = \asset(\sprintf('storage/%s', $filename));
        }
    }
}
