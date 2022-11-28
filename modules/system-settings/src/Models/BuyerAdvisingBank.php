<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerAdvisingBank extends Model
{
    use HasFactory;

    protected $table = 'buyer_advising_bank';

    protected $fillable = [
        'buyer_id',
        'advising_bank_id',
        'created_at',
        'updated_at',
    ];
}
