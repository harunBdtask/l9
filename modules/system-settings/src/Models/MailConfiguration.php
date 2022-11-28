<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class MailConfiguration extends Model
{
    protected $table = "mail_configurations";
    protected $primaryKey = "id";
    protected $fillable = [
        'driver',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'sending_time',
        'logo_url',
        'is_enabled',
    ];
}
