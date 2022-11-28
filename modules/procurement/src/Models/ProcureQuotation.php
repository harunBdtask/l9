<?php

namespace SkylarkSoft\GoRMG\Procurement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ProcureQuotation extends Model
{
    use HasFactory;

    protected $table = 'procure_quotations';

    protected $fillable = [
        'item_id',
        'supplier_id',
        'item_description',
        'uom_id',
        'unit_price',
        'last_modified_at',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by = Auth::id();
        });
    }

    public function item(): ?BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function supplier(): ?BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function uom(): ?BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}
