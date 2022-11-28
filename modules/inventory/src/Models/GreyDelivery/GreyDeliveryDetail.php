<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\GreyDelivery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\GreyReceive\GreyReceiveDetails;

class GreyDeliveryDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'grey_delivery_id',
        'grey_receive_details_id',
        'knitting_program_id',
        'plan_info_id',
        'knitting_program_roll_id',
        'challan_no',
    ];

    public function receiveDetail(): BelongsTo
    {
        return $this->belongsTo(GreyReceiveDetails::class, 'grey_receive_details_id')->withDefault();
    }


}
