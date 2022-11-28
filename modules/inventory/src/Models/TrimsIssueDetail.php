<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Casts\Json;
use App\Models\UIDModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsIssueDetail extends UIDModel
{
    use SoftDeletes;

    protected $table = 'trims_issue_details';

    protected $fillable = [
        'uniq_id',
        'trims_issue_id',
        'po_no',
        'item_id',
        'item_description',
        'brand_sup_ref',
        'item_color',
        'item_size',
        'uom_id',
        'issue_qty',
        'floor',
        'room',
        'rack',
        'shelf',
        'bin',
        'rate',
        'sewing_line_no',
        'style_name'
    ];

    protected $appends = [
        'floor_name',
        'room_name',
        'rack_name',
        'shelf_name',
        'bin_name',
    ];

    protected $casts = [
        'po_no' => Json::class
    ];

    public static function getConfig(): array
    {
        return ['abbr' => "TID"];
    }

    public function getFloorNameAttribute()
    {
        return $this->floorDetail()->first()->name ?? '';
    }

    public function getRoomNameAttribute()
    {
        return $this->roomDetail()->first()->name ?? '';
    }

    public function getRackNameAttribute()
    {
        return $this->rackDetail()->first()->name ?? '';
    }

    public function getShelfNameAttribute()
    {
        return $this->shelfDetail()->first()->name ?? '';
    }

    public function getBinNameAttribute()
    {
        return $this->binDetail()->first()->name ?? '';
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function trimsIssue(): BelongsTo
    {
        return $this->belongsTo(TrimsIssue::class, 'trims_issue_id')->withDefault();
    }

    public function trimsReceive(): BelongsTo
    {
        return $this->belongsTo(TrimsReceive::class, 'trims_receive_id')->withDefault();
    }

    public function trimsReceiveDetail(): BelongsTo
    {
        return $this->belongsTo(TrimsReceiveDetail::class, 'trims_receive_detail_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withDefault();
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class, 'sewing_line_no')->withDefault();
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $this->rate * $this->receive_qty;
    }

    public function floorDetail(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class, 'floor')->withDefault();
    }

    public function roomDetail(): BelongsTo
    {
        return $this->belongsTo(StoreRoom::class, 'room')->withDefault();
    }

    public function rackDetail(): BelongsTo
    {
        return $this->belongsTo(StoreRack::class, 'rack')->withDefault();
    }

    public function shelfDetail(): BelongsTo
    {
        return $this->belongsTo(StoreShelf::class, 'shelf')->withDefault();
    }

    public function binDetail(): BelongsTo
    {
        return $this->belongsTo(StoreBin::class, 'bin')->withDefault();
    }
}
