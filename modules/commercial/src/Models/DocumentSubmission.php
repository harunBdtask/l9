<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\Models\UIDModel;
use App\ModelCommonTrait;
use App\Models\BelongsToBuyer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\ExportProceedsRealization;

class DocumentSubmission extends UIDModel
{
    use SoftDeletes;
    use ModelCommonTrait;
    use BelongsToBuyer;

    protected $fillable = [
        'uniq_id',
        'factory_id',
        'buyer_id',
        'submission_date',
        'submitted_to',
        'lien_bank_id',
        'bank_ref_bill',
        'bank_ref_date',
        'submission_type',
        'dbp_type',
        'negotiation_date',
        'days_to_realize',
        'possible_reali_date',
        'courier_receipt_no',
        'courier_company',
        'gsp_courier_date',
        'bank_to_bank_cour_no',
        'bank_to_bank_cour_date',
        'currency_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function lienBank(): BelongsTo
    {
        return $this->belongsTo(LienBank::class, 'lien_bank_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(DocumentSubmissionInvoice::class, 'document_submission_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(DocumentSubmissionTransaction::class, 'document_submission_id');
    }

    public function proceed_realization(): hasMany
    {
        return $this->hasMany(ExportProceedsRealization::class, 'document_submission_id');
    }

    public static function getConfig(): array
    {
        return [
            'abbr' => 'DS',
            'field' => 'uniq_id',
        ];
    }
}
