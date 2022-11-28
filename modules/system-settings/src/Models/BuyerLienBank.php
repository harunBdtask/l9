<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerLienBank extends Model
{
    use HasFactory;

    protected $table = 'buyer_lien_bank';

    protected $fillable = [
        'buyer_id',
        'lien_bank_id',
        'created_at',
        'updated_at',
    ];

}
