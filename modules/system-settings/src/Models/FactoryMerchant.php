<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactoryMerchant extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'factory_merchants';

    protected $fillable = [
        'merchant_name', 'factory_address', 'factory_id', 'created_by', 'updated_by', 'deleted_at'
    ];
}
