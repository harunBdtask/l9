<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobNumber extends Model
{
    use HasFactory;

    protected $table = 'fi_job_numbers';

    protected $fillable = [
        'job_number',
        'description'
    ];
}
