<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Services\UID\TrimsInventoryService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class TrimsInventory extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'trims_inventories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bin_no',
        'style_name',
        'store_id',
        'buyer_id',
        'factory_id',
        'booking_id',
        'booking_no',
        'booking_date',
        'booking_qty',
        'delivery_qty',
        'challan_no',
        'challan_date',
        'qc_date',
        'pi_no',
        'pi_receive_date',
        'lc_no',
        'lc_receive_date',
        'tna_start_date',
        'tna_end_date',
        'actual_start_date',
        'actual_end_date',
        'others',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->bin_no = TrimsInventoryService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request, function (Builder $query) use ($request) {
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');
            $poNo = $request->query('pi_no');
            $challanNo = $request->query('challan_no');
            $challanDate = $request->query('challan_date');
            $binNo = $request->query('bin_no');
            $supplier = $request->query('supplier_id');
            $buyers = $request->query('buyer_id');
            $styleName = $request->query('style_name');
            $bookingNo = $request->query('booking_no');

            return $query->when($poNo, function (Builder $query) use ($poNo) {
                return $query->whereHas('booking', function (Builder $query) use ($poNo) {
                    return $query->whereHas('bookingDetails', function (Builder $query) use ($poNo) {
                        return $query->where('po_no', 'LIKE', "%{$poNo}%");
                    });
                });
            })->when($challanNo, Filter::applyFilter('challan_no', $challanNo))
                ->when($challanDate, Filter::applyFilter('challan_date', $challanDate))
                ->when($binNo, Filter::applyFilter('bin_no', $binNo))
                ->when($fromDate && $toDate, Filter::betweenFilter('challan_date', [$fromDate, $toDate]))
                ->when($supplier, function ($query) use ($supplier) {
                    return $query->whereHas('booking', function ($query) use ($supplier) {
                        return $query->where('supplier_id', $supplier);
                    });
                })
                ->when($styleName, function ($query) use ($styleName) {
                    return $query->where('style_name', $styleName);
                })
                ->when($buyers, function ($query) use ($buyers) {
                    return $query->where('buyer_id', $buyers);
                })
                ->when($bookingNo, Filter::applyFilter('booking_no', $bookingNo));
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsInventoryDetail::class, 'trims_inventory_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(TrimsBooking::class, 'booking_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class)->withDefault();
    }

}
