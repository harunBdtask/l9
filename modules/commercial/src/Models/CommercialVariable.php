<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialVariable extends Model
{
    use HasFactory;

    protected $table = 'commercial_variables';

    protected $fillable = [
        'factory_id',
        'variable_name',
        'value',
    ];
}
