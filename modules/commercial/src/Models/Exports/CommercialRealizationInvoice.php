<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Exports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmissionInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class CommercialRealizationInvoice extends Model
{
    use SoftDeletes;

    protected $table = 'commercial_realization_invoices';

    protected $fillable = [
        'commercial_realization_id',
        'realization_date',
        'document_submission_id',
        'dbp_type',
        'bank_ref_bill',
        'buyer_id',
        'document_submission_invoice_id',
        'export_lc_id',
        'primary_contract_id',
        'sales_contract_id',
        'export_invoice_id',
        'invoice_date',
        'net_invoice_value',
        'document_submission_date',
        'submission_value',
        'realized_value',
        'short_realized_value',
        'due_realized_value',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $appends = [
        'prev_realized_value',
    ];

    protected static function booted()
    {
        self::deleting(function ($model) {
            DB::table($model->table)
                ->where('id', $model->id)
                ->update([
                    'deleted_by' => userId(),
                ]);
        });
    }

    public function getPrevRealizedValueAttribute()
    {
        $prevQuery = self::query()
            ->where('commercial_realization_id', '!=', $this->attributes['commercial_realization_id'])
            ->where('document_submission_invoice_id', $this->attributes['document_submission_invoice_id']);
        $shortQuery = clone $prevQuery;
        $realizedValue = $prevQuery->sum('realized_value') ?? 0;
        $shortRealizedValue = $shortQuery->sum('short_realized_value') ?? 0;
        return $realizedValue + $shortRealizedValue;
    }

    public function commercialRealization(): BelongsTo
    {
        return $this->belongsTo(CommercialRealization::class, 'commercial_realization_id', 'id')->withDefault();
    }

    public function documentSubmission(): BelongsTo
    {
        return $this->belongsTo(DocumentSubmission::class, 'document_submission_id', 'id')->withDefault();
    }

    public function documentSubmissionInvoice(): BelongsTo
    {
        return $this->belongsTo(DocumentSubmissionInvoice::class, 'document_submission_invoice_id', 'id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }

    public function primaryContract(): BelongsTo
    {
        return $this->belongsTo(PrimaryMasterContract::class, 'primary_contract_id')->withDefault();
    }

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

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }
}
