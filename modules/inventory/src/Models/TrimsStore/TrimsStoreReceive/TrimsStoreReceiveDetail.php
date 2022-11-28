<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetail;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsStoreReceiveDetail extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'trims_store_receive_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'trims_inventory_detail_id',
        'trims_store_receive_id',
        'factory_id',
        'store_id',
        'current_date',
        'item_id',
        'item_description',
        'color_id',
        'size_id',
        'size',
        'planned_garments_qty',
        'booking_qty',
        'receive_qty',
        'receive_date',
        'receive_return_qty',
        'receive_return_date',
        'excess_qty',
        'uom_id',
        'rate',
        'total_receive_amount',
        'remarks',
        'approval_shade_code',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function trimsInventoryDetail(): BelongsTo
    {
        return $this->belongsTo(TrimsInventoryDetail::class, 'trims_inventory_detail_id', 'id')
            ->withDefault();
    }

    public function trimsStoreReceive(): BelongsTo
    {
        return $this->belongsTo(TrimsStoreReceive::class, 'trims_store_receive_id', 'id')
            ->withDefault();
    }

    public function mrrDetails(): HasMany
    {
        return $this->hasMany(TrimsStoreMrrDetail::class, 'trims_store_receive_detail_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function size()
    {
        //
    }

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }
}
