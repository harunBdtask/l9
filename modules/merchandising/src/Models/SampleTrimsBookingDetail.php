<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class SampleTrimsBookingDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'sample_trims_booking_id',
        'item_id',
        'uom_id',
        'requisition_no',
        'style_name',
        'dealing_merchant_id',
        'item_names',
        'item_des',
        'uom_values',
        'req_qty',
        'cu_wo',
        'balance_wo_qty',
        'wo_qty',
        'rate',
        'amount',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dealing_merchant_id');
    }
}
