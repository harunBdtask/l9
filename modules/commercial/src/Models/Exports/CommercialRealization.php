<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Exports;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmission;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CommercialRealization extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'commercial_realizations';

    protected $fillable = [
        'realization_date',
        'document_submission_id',
        'dbp_type',
        'bank_ref_bill',
        'conversion_rate',
        'buyer_id',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'commercialRealizationInvoices'
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

    public function documentSubmission(): BelongsTo
    {
        return $this->belongsTo(DocumentSubmission::class, 'document_submission_id', 'id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function commercialRealizationInvoices(): HasMany
    {
        return $this->hasMany(CommercialRealizationInvoice::class, 'commercial_realization_id', 'id');
    }
}
