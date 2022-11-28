<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportLCAmendmetDetail extends Model
{
    use HasFactory;

    protected $table = "export_lc_amendment_details";

    protected $fillable = [
        'amendment_id',
        'export_lc_id',
        'po_id',
        'order_id',
        'attach_qty',
        'rate',
        'attach_value',
    ];

    public function amendment()
    {
        return $this->belongsTo(ExportLCAmendment::class, 'amendment_id');
    }
}
