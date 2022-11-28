<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class ExportInvoiceDetail extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'export_invoice_details';

    protected $fillable = [
        'export_invoice_id',
        'export_lc_id',
        'export_lc_detail_id',
        'sales_contract_id',
        'sales_contract_detail_id',
        'order_id',
        'po_id',
        'article_no',
        'shipment_date',
        'attach_qty',
        'rate',
        'current_invoice_qty',
        'current_invoice_value',
        'cumu_invoice_qty',
        'po_balance_qty',
        'cumu_invoice_value',
        'ex_factory_qty',
        'merchandiser_id',
        'production_source',
        'color_size_details_status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function po(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function exportInvoice(): BelongsTo
    {
        return $this->belongsTo(ExportInvoice::class, 'export_invoice_id')->withDefault();
    }

    public function exportLcDetail(): BelongsTo
    {
        return $this->belongsTo(ExportLCDetail::class, 'export_lc_detail_id')->withDefault();
    }

    public function salesContractDetail(): BelongsTo
    {
        return $this->belongsTo(SalesContractDetail::class, 'sales_contract_detail_id')->withDefault();
    }

    public function purchaseOrders(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id')->withDefault();
    }

    public function colorSizeDetails(): HasMany
    {
        return $this->hasMany(ExportInvoiceColorSizeDetail::class, 'export_invoice_detail_id');
    }
}
