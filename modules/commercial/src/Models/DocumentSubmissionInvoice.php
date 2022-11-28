<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use App\Models\BelongsToBuyer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmission;

class DocumentSubmissionInvoice extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;
    use BelongsToBuyer;

    protected $table = 'document_submission_invoices';

    protected $fillable = [
        'document_submission_id',
        'buyer_id',
        'export_lc_id',
        'sales_contract_id',
        'export_invoice_id',
        'bl_no',
        'invoice_date',
        'net_inv_value',
        'po_ids',
        'factory_id',
    ];

    protected $casts = [
        'po_ids' => Json::class,
    ];

    public function exportInvoice(): BelongsTo
    {
        return $this->belongsTo(ExportInvoice::class, 'export_invoice_id')->withDefault();
    }

    public function exportLc(): BelongsTo
    {
        return $this->belongsTo(ExportLC::class, 'export_lc_id')->withDefault();
    }

    public function salesContract(): BelongsTo
    {
        return $this->belongsTo(SalesContract::class, 'sales_contract_id')->withDefault();
    }
    public function docSubmission(): BelongsTo
    {
        return $this->belongsTo(DocumentSubmission::class, 'document_submission_id','id');
    }
}
