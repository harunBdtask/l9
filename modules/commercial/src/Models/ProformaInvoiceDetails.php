<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaInvoiceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'booking_details_id',
        'booking_id',
        'type',
    ];
}
