<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\ServiceCompany;

class FabricIssue extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const ABBR = 'FI';
    const APPROVE = '1';
    const UNAPPROVE = '0';

    protected $table = 'fabric_issues';

    protected $primaryKey = 'id';

    protected $fillable = [
        'issue_no',
        'factory_id',
        'issue_date',
        'issue_purpose',
        'challan_no',
        'service_source',
        'service_company_type',
        'service_company_id',
        'service_location',
        'buyer_id',
        'cutt_req_no',
        'status',
        'outbound_buyer_name',
        'vehicle',
        'lock_no',
        'driver_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(FabricIssueDetail::class, 'issue_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function serviceCompany(): BelongsTo
    {
        return $this->belongsTo(ServiceCompany::class, 'service_company_id')->withDefault();
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->issue_no = getPrefix() . static::ABBR . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function scopeSearch($query,$search)
    {
        return $query->when($search,function ($query) use($search){
            $query->where('issue_no','LIKE','%'.$search.'%')
                ->orWhere('issue_date','LIKE','%'.$search.'%')
                ->orWhere('challan_no','LIKE','%'.$search.'%')
                ->orWhere('service_source','LIKE','%'.$search.'%')
                ->orWhereHas('buyer', function ($query) use ($search){
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('serviceCompany', function ($query) use ($search){
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });;
        });
    }
}
