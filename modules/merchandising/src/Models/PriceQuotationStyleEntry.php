<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceQuotationStyleEntry extends Model
{
    protected $table = 'price_quotation_style_entries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'price_quotation_id',
        'pcs_per_carton',
        'cbm_per_carton'
    ];

    public function priceQuotation(): BelongsTo
    {
        return $this->belongsTo(PriceQuotation::class, 'price_quotation_id', 'id');
    }
}
