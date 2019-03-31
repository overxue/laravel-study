<?php

namespace App\Observers;

use App\Models\User;
use Avatar;
use Overtrue\Pinyin\Pinyin;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public function saving(User $user)
    {
        if (empty($user->avatar)) {
            $user_name = app(Pinyin::class)->abbr($user->name);
            Avatar::create($user_name)->setDimension(300, 300)->save('a.png');
            $user->avatar = 'http://larabbs.test/a.png';
        }
    }
}
