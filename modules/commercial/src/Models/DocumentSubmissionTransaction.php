<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentSubmissionTransaction extends Model
{
    protected $table = 'document_submission_transactions';

    protected $fillable = [
        'document_submission_id',
        'account_head_id',
        'ac_loan_no',
        'domestic_currency',
        'conversion_rate',
        'lc_sc_currency',
        'factory_id',
    ];

    public function documentSubmission(): BelongsTo
    {
        return $this->belongsTo(DocumentSubmission::class, 'document_submission_id')
            ->withDefault();
    }

    public function accountHead(): BelongsTo
    {
        return $this->belongsTo(AccountHead::class, 'account_head_id')->withDefault();
    }
}
