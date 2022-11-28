<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\GreyReceive;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GreyReceive extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'factory_id',
        'factory_name',
        'source_id',
        'source_name',
        'received_type_id',
        'received_type_value',
        'challan_no',
    ];

    public function details()
    {
        return $this->hasMany(GreyReceiveDetails::class, 'grey_receive_id', 'id');
    }
}
