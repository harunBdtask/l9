<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationGroup extends Model
{
    protected $fillable = [
        'name',
        'users'
    ];

    protected $casts = [
        'users' => "array"
    ];

    protected $appends = [
        'user_notifications'
    ];

    public function getUserNotificationsAttribute(): string
    {
        return is_array($this->users) ? User::query()
            ->whereIn('id', $this->users)
            ->pluck('email')
            ->implode(', ') : '';
    }
}
