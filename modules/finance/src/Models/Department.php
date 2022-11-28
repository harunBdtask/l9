<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fi_departments';

    protected $fillable = [
        'department',
        'dept_details',
    ];

    protected $dates = ['deleted_at'];

}
