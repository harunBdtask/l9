<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class MailGroup extends Model
{
    protected $table = "mail_groups";
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'users'
    ];

    protected $casts = [
        'users' => "array"
    ];

    protected $appends = [
        'user_emails'
    ];

    public function getUserEmailsAttribute(): string
    {
        return is_array($this->users) ? User::query()
            ->whereIn('id', $this->users)
            ->pluck('email')
            ->implode(', ') : '';
    }
}
