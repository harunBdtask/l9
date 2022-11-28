<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BfVariableSetting extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'bf_variable_settings';

    protected $fillable = [
        'factory_id',
        'departmental_approval',
        'voucher_preview_signature',
        'accounting_users',
    ];

    protected $casts = [
        'accounting_users' => Json::class
    ];
}
