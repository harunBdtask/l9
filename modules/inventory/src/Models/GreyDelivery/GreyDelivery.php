<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\GreyDelivery;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GreyDelivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'factory_id',
        'factory_name',
        'challan_no',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(GreyDeliveryDetail::class, 'grey_delivery_id', 'id');
    }
}
