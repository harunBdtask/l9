<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Contracts\AuditAbleContract;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class QuotationInquiryDetail extends Model implements AuditAbleContract
{
    use HasFactory;
    use SoftDeletes;
    use AuditAble;

    protected $table = "quotation_inquiry_details";

    protected $fillable = [
        'quotation_id',
        'fabrication',
        'fabric_composition',
        'gsm',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::saving(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::updating(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'updated_by' => auth()->user()->id,
            ]);
        });
        static::deleting(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'deleted_by' => auth()->user()->id,
            ]);
        });
    }

    public function quotationInquiry(): BelongsTo
    {
        return $this->belongsTo(QuotationInquiry::class, 'quotation_id', 'quotation_id')->withDefault();
    }

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return url("/quotation-inquiries/$this->quotation_id/edit");
    }
}
