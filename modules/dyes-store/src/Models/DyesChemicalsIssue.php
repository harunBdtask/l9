<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\DyesStore\Services\DyesChemicalIssueService;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;

class DyesChemicalsIssue extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    /**
     * @var string
     */
    protected $table = 'dyes_chemicals_issues';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'system_generate_id',
        'to',
        'customer_id',
        'delivery_date',
        'requisition',
        'store_id',
        'details', // Json
        'readonly',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'readonly' => 'boolean',
        'details' => Json::class,
    ];

    public function scopeFilter($query, $search)
    {
       return $query->when($search, function ($query) use ($search) {
            $query->whereHas('customer', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('details', 'like', '%' . $search . '%');
        });
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->system_generate_id = DyesChemicalIssueService::generateUniqueId();
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(DsCustomer::class, 'customer_id')->withDefault();
    }

    /**
     * Boot Function
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });

        static::updated(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleted(function ($model) {
            $model->deleted_by = Auth::user()->id;
        });
    }
}
