<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesContractAmendmentDetail extends Model
{
    protected $fillable = [
        'amendment_id',
        'sales_contract_id',
        'po_id',
        'order_id',
        'attach_qty',
        'rate',
        'attach_value',
    ];

    /**
     * @return BelongsTo
     */
    public function amendment(): BelongsTo
    {
        return $this->belongsTo(SalesContractAmendment::class, 'amendment_id')->withDefault();
    }
}
