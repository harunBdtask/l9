<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleEntry extends Model
{
    protected $table = 'style_entries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'pcs_per_carton',
        'cbm_per_carton'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
