<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsAccessory extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'trims_accessories';

    protected $fillable = [
        'order_id',
        'budget_master_id',
        'supplier_id',
        'attention_name',
        'break_down_type',
        'item_id',
        'general_percentage',
        'unit_of_measurement_id',
        'thread_consumption',
        'cone_meter',
        'all_color_all_size_unit_price',
        'quality',
        'delivery_date',
        'wash_note',
        'order_note',
        'delivery_factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $cascadeDeletes = [
        'trimsAccessoryDetails',
    ];

    public function trimsAccessoryDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TrimsAccessoryDetail::class);
    }

    public function unitOfMeasurement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id')->withDefault();
    }

    public function budgetMaster(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BudgetMaster::class, 'budget_master_id')->withDefault();
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withDefault();
    }

    public function deliveryFactory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'delivery_factory_id')->withDefault();
    }
}
