<?php


namespace SkylarkSoft\GoRMG\Commercial\Models\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\Models\AccountHead;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmissionTransaction;

class ExportProceedDistribution extends Model
{
    use SoftDeletes;

    protected $table = 'export_proceed_details';

    protected $fillable = [
        'export_proceed_realization_id',
        'document_submission_transaction_id',
        'account_head_id',
        'ac_loan_no',
        'document_currency',
        'conversion_rate',
        'domestic_currency',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('distributionStatus', function (Builder $builder) {
            $table = $builder->getModel()->getTable();
            $builder->where(($table ? $table.'.' : '').'status', CommercialConstant::ExportProceedDistributionStatus);
        });

        static::created(function ($model) {
            $model->status = CommercialConstant::ExportProceedDistributionStatus;
        });
    }

    public function exportProceedRealization(): BelongsTo
    {
        return $this->belongsTo(ExportProceedsRealization::class, 'export_proceed_realization_id')->withDefault();
    }

    public function documentSubmissionTransaction()
    {
        return $this->belongsTo(DocumentSubmissionTransaction::class, 'document_submission_transaction_id')->withDefault();
    }

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class, 'account_head_id')->withDefault();
    }
}
