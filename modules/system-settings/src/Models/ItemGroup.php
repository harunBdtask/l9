<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemGroup extends Model
{
    use SoftDeletes;

    protected $table = 'item_groups';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = Auth::id();
        });
        static::updating(function ($post) {
            $post->updated_by = Auth::id();
        });
    }

    public function scopeSearch($query, $searchKey)
    {
        return $query->when($searchKey, function ($query) use ($searchKey) {
            $query->where('product_id', 'LIKE', "%$searchKey%")
                ->orWhere('item_group', 'LIKE', "%$searchKey%")
                ->orWhere('trims_type', 'LIKE', "%$searchKey%")
                ->orWhereHas('item', function ($query) use ($searchKey) {
                    $query->where('item_name', 'LIKE', "%$searchKey%");
                });
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withDefault();
    }

    public function orderUOM(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'order_uom')->withDefault();
    }

    public function consUOM(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'cons_uom')->withDefault();
    }

    public function item_group_assign(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroupAssign', 'item_group_id', 'id');
    }

    public function itemSubGroup(): BelongsTo
    {
        return $this->belongsTo(ItemSubgroup::class, 'item_subgroup_id')->withDefault();
    }

    public function group(): HasOne
    {
        return $this->hasOne(GroupWiseField::class, 'group_name')->withDefault();
    }

    public function prepared_by(): BelongsTo
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'created_by',
            'id'
        );
    }

    public function editted_by(): BelongsTo
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'updated_by',
            'id'
        );
    }

    public function deleted_by_user(): BelongsTo
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'deleted_by',
            'id'
        );
    }
    public function controlLedgerAcc(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'control_ledger','id')->withDefault();
    }
    public function ledgerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'ledger_ac','id')->withDefault();
    }
}
