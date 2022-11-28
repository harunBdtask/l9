<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;

class FabricReceive extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const APPROVE = '1';
    const UNAPPROVE = '0';

    protected $table = 'fabric_receives';

    const FABRIC_BOOKING = 'fabric-booking';
    const SHORT_BOOKING = 'short-fabric-booking';
    const PROFORMA_INVOICE = 'proforma-invoice';

    const ABBR = 'FR';

    const INDEPENDENT_BASIS = 'independent';
    const BOOKING_BASIS = 'booking_basis';
    const PI_BASIS = 'pi_basis';

    protected $fillable = [
        'receive_no',
        'factory_id',
        'factory_location',
        'receive_date',
        'store_id',
        'receive_basis',
        'receivable_type',
        'receivable_id',
        'dyeing_source',
        'dyeing_supplier_type',
        'dyeing_supplier_id',
        'dyeing_supplier_address',
        'receive_challan',
        'po_no',
        'grey_issue_challan',
        'currency_id',
        'exchange_rate',
        'lc_sc_no',
        'pi_offer_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'po_no' => Json::class
    ];

    public function details(): HasMany
    {
        return $this->hasMany(FabricReceiveDetail::class, 'receive_id');
    }

    public function barcodeDetails(): HasMany
    {
        return $this->hasMany(FabricBarcodeDetail::class, 'fabric_receive_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Stores::class, 'store_id')->withDefault();
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(FabricBooking::class, 'receivable_id')->withDefault();
    }

    public function bookingDetailsBreakdown(): HasMany
    {
        return $this->hasMany(FabricBookingDetailsBreakdown::class, 'booking_id', 'receivable_id');
    }

    public function receivable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->receive_no = getPrefix() . static::ABBR . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function prevQty($fromDate, $toDate)
    {
        return $this->where('receive_date', '>', $fromDate)->where('receive_date', '<', $toDate)->sum('details.receive_qty');
    }

    public function scopeSearch($query, $search)
    {
         return $query->when($search, function ($query) use ($search) {
            $query->where('receive_basis', 'LIKE', '%' . $search . '%')
                ->orWhere('receive_no', 'LIKE', '%' . $search . '%')
                ->orWhere('receive_challan', 'LIKE', '%' . $search . '%')
                ->orWhere('receive_date', 'LIKE', '%' . $search . '%')
                ->orWhereHas('booking', function ($query) use ($search) {
                    $query->where('unique_id', 'LIKE', '%' . $search . '%');
                })->orWhereHas('details', function ($query) use ($search) {
                    $query->WhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                    })->orWhere('style_name', 'LIKE', '%' . $search . '%');
                });
        });
    }

    public function fabricIssue(): HasMany
    {
        return $this->hasMany(FabricIssueDetail::class,'fabric_receive_id');
    }
}
