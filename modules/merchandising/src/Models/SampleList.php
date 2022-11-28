<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleList extends Model
{
    protected $table = 'sample_type';
    protected $primary_key = 'id';
    protected $fillable = [
        'sample_type',
        'created_at',
        'updated_at',
    ];

    protected $dates = ['deleted_at'];
}
