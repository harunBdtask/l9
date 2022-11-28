<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use App\Models\BelongsToSupplier;
use App\Models\UIDModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class TrimsReceive extends UIDModel
{
    use SoftDeletes;
    use ModelCommonTrait;
    use BelongsToSupplier;

    protected $table = 'trims_receives';

    const BOOKING_BASIS = 'work_order';
    const PI_BASIS = 'pi_basis';

    protected $fillable = [
        'uniq_id',
        'booking_type',
        'buyer_id',
        'receive_basic',
        'factory_id',
        'store_id',
        'receive_date',
        'challan_no',
        'supplier_id',
        'pay_mode',
        'source',
        'lc_no',
        'currency_id',
        'challan_date',
        'exchange_rate',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function getConfig(): array
    {
        return ['abbr' => "TR"];
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where('receive_basic', 'LIKE', "%${search}%")
                ->orWhere('challan_no', 'LIKE', "%${search}%");
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsReceiveDetail::class, 'trims_receive_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function scopeForStyleName(Builder $query, $styleName): Builder
    {
        return $query->whereHas('details', function (Builder $query) use ($styleName) {
            return $query->where('style_name', $styleName);
        });
    }

    public function scopeIncludesPO(Builder $query, array $poNo): Builder
    {
        return $query->whereHas('details', function (Builder $query) use ($poNo) {
            return $query->whereJsonContains('po_no', $poNo);
        });
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class,'currency_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class,'factory_id')->withDefault();
    }
}
