<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Contracts\AuditAbleContract;
use App\Traits\AuditAble;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class QuotationInquiry extends Model implements AuditAbleContract
{
    use HasFactory;
    use SoftDeletes;
    use AuditAble;
    use CascadeSoftDeletes;

    protected $table = "quotation_inquiries";

    protected $fillable = [
        'quotation_id',
        'factory_id',
        'buyer_id',
        'style_name',
        'style_description',
        'garment_item_id',
        'season_id',
        'status',
        'inquiry_date',
        'dealing_merchant',
        'submission_date',
        'approval_date',
        'required_sample',
        'remarks',
        'file_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'quotationInquiryDetails',
        'priceQuotations',
    ];

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

    const STATUS = ['1' => 'Active', '2' => 'Inactive'];
    const REQUIRED_SAMPLE = ['1' => 'Yes', '2' => 'No'];

    public function setInquiryDateAttribute($value)
    {
        $this->attributes['inquiry_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function setSubmissionDateAttribute($value)
    {
        $this->attributes['submission_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function setApprovalDateAttribute($value)
    {
        $this->attributes['approval_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function quotationInquiryDetails()
    {
        return $this->hasMany(QuotationInquiryDetail::class, 'quotation_id', 'quotation_id');
    }

    public function priceQuotations()
    {
        return $this->hasMany(PriceQuotation::class, 'quotation_inquiry_id', 'quotation_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'id')->withDefault();
    }

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return url("/quotation-inquiries/$this->id/edit");
    }
}
