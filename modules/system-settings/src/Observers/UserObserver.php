<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Observers;

use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        $this->updateUserCache();
    }



    /**
     * Handle the user "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        $this->updateUserCache();
    }


    /**
     * cache value update
     */
    public function updateUserCache(): bool
    {
        $users_count = User::count();
        Cache::put('users_count', $users_count, 1440);

        $online_users = User::where('status', true)->get();
        Cache::put('onlineUsers', $online_users, 1440);

        return true;
    }
}
