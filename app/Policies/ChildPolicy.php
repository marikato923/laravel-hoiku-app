<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;
use App\Models\Admin;

class ChildPolicy
{
    /**
     * 子供情報の閲覧を許可するかどうか
     */
    public function view(User|Admin $user, Child $child): bool
    {
        return auth('admin')->check() || $user->id === $child->user_id;
    }

    /**
     * 子供情報の登録を許可するかどうか
     */
    public function create(User|Admin $user): bool
    {
        return auth('admin')->check() || auth()->check();
    }

    /**
     * 子供情報の編集を許可するかどうか
     */
    public function update($user, Child $child): bool
    {
        if ($user instanceof Admin) {
            return true; 
        }

        if ($user instanceof User) {
            return $user->id === $child->user_id; 
        }

        return false; 
    }

    /**
     * 子供情報の削除を許可するかどうか
     */
    public function delete(User|Admin $user, Child $child): bool
    {
        return auth('admin')->check();
    }
}
