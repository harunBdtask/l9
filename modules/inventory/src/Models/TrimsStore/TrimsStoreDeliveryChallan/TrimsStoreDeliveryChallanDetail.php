<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\StoreBin;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsStoreDeliveryChallanDetail extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;

    protected $table = 'trims_store_delivery_challan_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'trims_store_delivery_challan_id',
        'trims_store_issue_detail_id',
        'factory_id',
        'store_id',
        'item_id',
        'item_description',
        'issue_date',
        'issue_qty',
        'color_id',
        'size_id',
        'size',
        'issue_return_qty',
        'uom_id',
        'approval_shade_code',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'issue_to',
        'issue_purpose',
        'remarks',
        'planned_garments_qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function issueDetail(): BelongsTo
    {
        return $this->belongsTo(TrimsStoreIssueDetail::class, 'trims_store_issue_detail_id')->withDefault();
    }

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class, 'floor_id')->withDefault();
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(StoreRoom::class, 'room_id')->withDefault();
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(StoreRack::class, 'rack_id')->withDefault();
    }

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(StoreShelf::class, 'shelf_id')->withDefault();
    }

    public function bin(): BelongsTo
    {
        return $this->belongsTo(StoreBin::class, 'bin_id')->withDefault();
    }
}
