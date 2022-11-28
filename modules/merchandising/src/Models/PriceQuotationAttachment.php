<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;

class PriceQuotationAttachment extends Model
{
    protected $table = "price_quotation_attachments";
    protected $fillable = [
        'price_quotation_id',
        'type',
        'name',
        'path',
    ];
}
