<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Imports;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\AdjustSourceService;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\PaymentHeadService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;

class ImportPayment extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'import_payments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'import_document_acceptance_id',
        'payment_date',
        'payment_head_id',
        'adj_source_id',
        'conversion_rate',
        'accepted_amount',
        'currency_id',
        'remarks',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = ['payment_head', 'adj_source'];

    public function getPaymentHeadAttribute()
    {
        return $this->payment_head_id ? collect(PaymentHeadService::data())->where('id', $this->payment_head_id)->first()['text'] : null;
    }

    public function getAdjSourceAttribute()
    {
        return $this->adj_source_id ? collect(AdjustSourceService::data())->where('id', $this->adj_source_id)->first()['text'] : null;
    }

    public function scopeFilter($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            $query->whereHas('currency', function ($q) use ($value) {
                $q->where('currency_name', 'LIKE', "%${value}");
            })->orWhereHas('factory', function ($q) use ($value) {
                $q->where('factory_name', 'LIKE', "%${value}%");
            });
        });
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function importDocumentAcceptance(): BelongsTo
    {
        return $this->belongsTo(ImportDocumentAcceptance::class, 'import_document_acceptance_id')->withDefault();
    }
}
