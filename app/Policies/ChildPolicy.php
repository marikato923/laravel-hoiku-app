<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Child;

class ChildPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user, Child $child)
    {
        //　管理者ユーザーはすべての子どもにアクセス可能
        if (auth('admin')->check()) {
            return true;
        }
        // 一般ユーザーは自分の子供にのみアクセス可能
        return $user->id === $child->user_id;
    }
}
