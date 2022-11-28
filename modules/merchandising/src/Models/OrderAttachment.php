<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAttachment extends Model
{
    protected $table = "order_attachments";
    protected $fillable = [
        'order_id',
        'type',
        'name',
        'path',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }
}
