<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Exports;

use App\ModelCommonTrait;
use App\Models\BelongsToBuyer;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmission;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class ExportProceedsRealization extends Model
{
    use ModelCommonTrait;
    use SoftDeletes;
    use BelongsToBuyer;
    use CascadeSoftDeletes;

    protected $table = 'export_proceed_realizations';

    protected $fillable = [
        'beneficiary_id',
        'buyer_id',
        'document_submission_id',
        'export_lc_id',
        'sales_contract_id',
        'receive_date',
        'lc_sc_no',
        'currency_id',
        'bill_invoice_date',
        'bill_invoice_amount',
        'negotiated_amount',
        'document_currency',
        'domestic_currency',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'deductions',
        'distributions',
    ];

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    public function exportLc(): BelongsTo
    {
        return $this->belongsTo(ExportLC::class, 'export_lc_id')->withDefault();
    }

    public function salesContract(): BelongsTo
    {
        return $this->belongsTo(SalesContract::class, 'sales_contract_id')->withDefault();
    }

    public function documentSubmission(): BelongsTo
    {
        return $this->belongsTo(DocumentSubmission::class, 'document_submission_id')->withDefault();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function deductions()
    {
        return $this->hasMany(ExportProceedDeduction::class, 'export_proceed_realization_id', 'id');
    }

    public function distributions()
    {
        return $this->hasMany(ExportProceedDistribution::class, 'export_proceed_realization_id', 'id');
    }
}
