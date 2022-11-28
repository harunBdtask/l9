<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryLienBank extends Model
{
    use HasFactory;

    protected $table = 'factory_lien_bank';

    protected $fillable = [
        'factory_id',
        'lien_bank_id',
        'created_at',
        'updated_at',
    ];
}
