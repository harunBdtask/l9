<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use SkylarkSoft\GoRMG\SystemSettings\Models\{Buyer, Currency, Factory, Season, Supplier, Team};;

class FabricSalesOrder extends Model
{
    use SoftDeletes, CommonModelTrait;
    protected $table = 'fabric_sales_orders';
    protected $fillable = [
        'sales_order_no',
        'factory_id',
        'location',
        'currency_id',
        'ship_mode',
        'attention',
        'remarks',
        'ready_to_approve',
        'within_group',
        'delivery_date',
        'unit_id',
        'team_leader',
        'season_id',
        'fabric_composition',
        'unapproved_request',
        'booking_no',
        'booking_type',
        'booking_date',
        'receive_date',
        'style_name',
        'dealing_merchant',
        'buyer_id',
        'booking_type_status',
        'order_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    const WITHIN_GROUP = [
        1 => 'Yes',
        2 => 'No'
    ];

    const BOOKING_TYPE_STATUS = [
        1 => 'Sample',
        2 => 'Bulk'
    ];

    const ORDER_STATUS = [
        1 => 'Projection',
        2 => 'Confirmed',
        3 => 'Canceled',
        4 => 'In-house',
        5 => 'Sample',
    ];

    const UNITS = [
        1 => Factory::class,
        2 => Supplier::class,
    ];

    protected $appends = [
        'within_group_text'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->sales_order_no = getPrefix() . 'FSOE-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function getWithinGroupTextAttribute(): string
    {
        return isset($this->attributes['within_group']) ? self::WITHIN_GROUP[$this->attributes['within_group']] : '';
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(FabricBooking::class, 'booking_no','id');
    }

    public function buyerData(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function teamLeader(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_leader_id')->withDefault();
    }

    public function breakdown(): HasMany
    {
        return $this->hasMany(FabricSalesOrderDetail::class, 'fabric_sales_order_id');
    }

    public function program(): HasOne
    {
        return $this->hasOne(KnittingProgram::class, 'fabric_sales_order_id', 'id');
    }

    public function planInfo()
    {
        return $this->morphOne(PlanningInfo::class, 'programmable');
    }

    public function planInfoMany()
    {
        return $this->morphMany(PlanningInfo::class, 'programmable');
    }

    // Polymorphic relation
    public function bookingDetails()
    {
        return $this->hasMany(FabricSalesOrderDetail::class, 'fabric_sales_order_id');
    }

    public function factory(){
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function unit(): BelongsTo
    {
        $relationShipClass = self::UNITS[$this->within_group ?? 1];
        return $this->belongsTo($relationShipClass, 'unit_id', 'id')->withDefault();
    }
    public function getUnitDataAttribute()
    {
        $unit = Supplier::query()->find($this->unit_id);
        if($this->within_group===1){
           $unit = Factory::query()->find($this->unit_id);
            return ['id'=>optional($unit)->id, 'text'=>optional($unit)->factory_name];
        }
        return ['id'=>optional($unit)->id, 'text'=>optional($unit)];

    }
    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function season(){
        return $this->belongsTo(Season::class, 'season_id')->withDefault();
    }

}
