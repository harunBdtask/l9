<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mailing',
        'teamleader_id'
    ];

    const  STATUS = [
        0=>'Inactive',
        1=>'Active'
    ];
}
