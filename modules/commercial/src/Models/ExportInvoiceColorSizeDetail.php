<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class ExportInvoiceColorSizeDetail extends Model
{
    use SoftDeletes;

    protected $table = 'export_invoice_color_size_details';

    protected $fillable = [
        'export_invoice_detail_id',
        'order_id',
        'po_id',
        'garments_item_id',
        'color_id',
        'size_id',
        'article_no',
        'po_qty',
        'po_rate',
        'po_amount',
        'invoice_qty',
        'invoice_rate',
        'invoice_amount',
    ];

    public function exportInvoiceDetail(): BelongsTo
    {
        return $this->belongsTo(ExportInvoiceDetail::class, 'export_invoice_detail_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id')->withDefault();
    }

    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }
}
