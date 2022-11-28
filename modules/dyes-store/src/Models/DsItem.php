<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;

class DsItem extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    /**
     * @var string
     */
    protected $table = 'ds_inv_items';

    const ROUND_PRE = 2;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'brand_id',
        'uom',
        'store',
        'prefix',
        'company_id',
        'barcode',
        'qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'avl_qty',
    ];

    /**
     * @param $query
     * @param $search
     */
    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('brand', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('store_details', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    /**
     * @param null $fromDate
     * @param null $toDate
     * @return int[]
     */
    public function stock($fromDate = null, $toDate = null): array
    {
        $stock = [
            'opening_balance' => 0,
            'opening_rate' => 0,
            'opening_value' => 0,
            'inwards' => 0,
            'inward_rate' => 0,
            'inward_value' => 0,
            'outwards' => 0,
            'outward_rate' => 0,
            'outward_value' => 0,
            'closing_balance' => 0,
            'closing_rate' => 0,
            'closing_value' => 0,
        ];
        $stockIn = $this->stockIns()->where('trn_date', '<=', Carbon::parse($fromDate)->subDay())
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stockOut = $this->stockOuts()->where('trn_date', '<=', Carbon::parse($fromDate)->subDay())
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['opening_balance'] = round($stockIn->total_quantity - $stockOut->total_quantity, self::ROUND_PRE);
        $stock['opening_value'] = round($stockIn->stock_value - $stockOut->stock_value, self::ROUND_PRE);
        $stock['opening_rate'] = ($stock['opening_balance'] != 0) ? round(($stock['opening_value'] / $stock['opening_balance']), self::ROUND_PRE) : 0;
        $stock['opening_value'] = round($stock['opening_balance'] * $stock['opening_rate'], self::ROUND_PRE);
        $stockIn = $this->stockIns()->where('trn_date', '>=', Carbon::parse($fromDate))
            ->where('trn_date', '<=', Carbon::parse($toDate))
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['inwards'] = round($stockIn->total_quantity, self::ROUND_PRE) ?? 0;
        $stock['inward_value'] = round($stockIn->stock_value, self::ROUND_PRE) ?? 0;
        $stock['inward_rate'] = ($stock['inwards'] != 0) ? round(($stock['inward_value'] / $stock['inwards']), self::ROUND_PRE) : 0;
        $stock['inward_value'] = round($stock['inwards'] * $stock['inward_rate'], self::ROUND_PRE);
        $stockOut = $this->stockOuts()->where('trn_date', '>=', Carbon::parse($fromDate))
            ->where('trn_date', '<=', Carbon::parse($toDate))
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['outwards'] = round($stockOut->total_quantity, self::ROUND_PRE) ?? 0;
        $stock['outward_value'] = round($stockOut->stock_value, self::ROUND_PRE) ?? 0.00;
        $stock['outward_rate'] = ($stock['outwards'] != 0) ? round(($stock['outward_value'] / $stock['outwards']), self::ROUND_PRE) : 0;
        $stock['outward_value'] = round($stock['outwards'] * $stock['outward_rate'], self::ROUND_PRE);
        $stock['closing_balance'] = round($stock['opening_balance'] + $stock['inwards'] - $stock['outwards'], self::ROUND_PRE);
        $stock['closing_value'] = round($stock['opening_value'] + $stock['inward_value'] - $stock['outward_value'], self::ROUND_PRE);
        $stock['closing_rate'] = ($stock['closing_balance'] != 0) ? round(($stock['closing_value'] / $stock['closing_balance']), self::ROUND_PRE) : 0;
        $stock['closing_value'] = round($stock['closing_balance'] * $stock['closing_rate'], self::ROUND_PRE);

        return $stock;
    }

    /**
     * @param $date
     * @return array
     */
    public function itemStock($date): array
    {
        $stock = [
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'inwards' => 0,
            'inward_rate' => 0,
            'inward_value' => 0,
            'outwards' => 0,
            'outward_rate' => 0,
            'outward_value' => 0,
            'closing_balance' => 0,
            'closing_rate' => 0,
            'closing_value' => 0,
            'voucher_no' => ''
        ];
        $stockIn = $this->stockIns()->with('voucher')->where('trn_date', '>=', Carbon::parse($date))
            ->where('trn_date', '<=', Carbon::parse($date))
            ->select('*', DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['inwards'] = round($stockIn->total_quantity, self::ROUND_PRE) ?? 0;
        $stock['inward_value'] = round($stockIn->stock_value, self::ROUND_PRE) ?? 0;
        $stock['inward_rate'] = ($stock['inwards'] != 0) ? round(($stock['inward_value'] / $stock['inwards']), self::ROUND_PRE) : 0;
        $stock['inward_value'] = round($stock['inwards'] * $stock['inward_rate'], self::ROUND_PRE);
        $stockOut = $this->stockOuts()->where('trn_date', '>=', Carbon::parse($date))
            ->where('trn_date', '<=', Carbon::parse($date))
            ->select('*', DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['outwards'] = round($stockOut->total_quantity, self::ROUND_PRE) ?? 0;
        $stock['outward_value'] = round($stockOut->stock_value, self::ROUND_PRE) ?? 0.00;
        $stock['outward_rate'] = ($stock['outwards'] != 0) ? round(($stock['outward_value'] / $stock['outwards']), self::ROUND_PRE) : 0;
        $stock['outward_value'] = round($stock['outwards'] * $stock['outward_rate'], self::ROUND_PRE);
        $stock['closing_balance'] = round($stock['inwards'] - $stock['outwards'], self::ROUND_PRE);
        $stock['closing_value'] = round($stock['inward_value'] - $stock['outward_value'], self::ROUND_PRE);
        $stock['closing_rate'] = ($stock['closing_balance'] != 0) ? round(($stock['closing_value'] / $stock['closing_balance']), self::ROUND_PRE) : 0;
        $stock['closing_value'] = round($stock['closing_balance'] * $stock['closing_rate'], self::ROUND_PRE);
        $stock['voucher_no'] = isset($stockIn->voucher) ? $stockIn->voucher->voucher_no : $stockOut->voucher->voucher_no;

        return $stock;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    /**
     * @param Builder $query
     * @param $store
     * @return Builder
     */
    public function scopeStore(Builder $query, $store): Builder
    {
        return $query->where('store', $store);
    }

    /**
     * @return mixed|null
     */
    public function getAvlQtyAttribute()
    {
        return ($this->in_qty - $this->out_qty) ?: null;
    }

    /*
     * relations
     */

    /**
     * @return BelongsTo
     */
    public function uomDetails(): BelongsTo
    {
        return $this->belongsTo(DsUom::class, "uom")->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DsInvItemCategory::class, 'category_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(DsBrand::class, 'brand_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function store_details(): BelongsTo
    {
        return $this->belongsTo(DsStoreModel::class, 'store')->withDefault();
    }

    /**
     * @return HasMany
     */
    public function dyesStockIns(): HasMany
    {
        return $this->dyesTransactions()->with('dyesChemicalsReceive')->where("trn_type", "in");
    }

    /**
     * @return HasMany
     */
    public function dyesStockOuts(): HasMany
    {
        return $this->dyesTransactions()->where("trn_type", "out");
    }

    /**
     * @return HasMany
     */
    public function dyesTransactions(): HasMany
    {
        return $this->hasMany(DyesChemicalTransaction::class, "item_id", "id")->with('dyesChemicalsReceive');
    }

    /**
     * @return HasMany
     */
    public function dyesTransaction(): HasMany
    {
        return $this->hasMany(DyesChemicalTransaction::class, 'item_id');
    }

    public function latestDyesTransaction(): HasOne
    {
        return $this->hasOne(DyesChemicalTransaction::class, 'item_id')
            ->latestOfMany()
            ->withDefault(['rate' => 0]);
    }

    public function dyesItemWiseLifeEndDays(): HasMany
    {
        return $this->dyesTransaction()->where('trn_type', 'in')->groupBy('life_end_days', 'trn_date');
    }

    /**
     * @param $query
     */
    public function scopeWithAvlQty($query)
    {
        $query->addSubSelect('avl_qty', function ($query) {
            $aggregator = 'sum(case `trn_type` when "in" then qty when "out" then qty * -1 end) as qty';
            $query->select(DB::raw($aggregator))
                ->from('inv_transactions')
                ->whereColumn('item_id', 'inv_items.id');
        });
    }

    /**
     * @param null $fromDate
     * @param null $toDate
     * @return int[]
     */
    public function dyesStock($fromDate = null, $toDate = null, $storeId = null): array
    {
        $stock = [
            'opening_balance' => 0,
            'opening_rate' => 0,
            'opening_value' => 0,
            'inwards' => 0,
            'inward_rate' => 0,
            'inward_value' => 0,
            'outwards' => 0,
            'outward_rate' => 0,
            'outward_value' => 0,
            'closing_balance' => 0,
            'closing_rate' => 0,
            'closing_value' => 0,
        ];

        // Opening balance section...
        $stockIn = $this->dyesStockIns()->where('trn_date', '<=', Carbon::parse($fromDate)->subDay())
            ->when($storeId == null, function ($query) use ($storeId) {
                $query->whereNull('sub_store_id');
            })
            ->when($storeId, function ($query) use ($storeId) {
                $query->where('sub_store_id', $storeId);
            })
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity, trn_date'))
            ->first();
        $stockOut = $this->dyesStockOuts()->where('trn_date', '<=', Carbon::parse($fromDate)->subDay())
            ->when($storeId == null, function ($query) use ($storeId) {
                $query->whereNull('sub_store_id');
            })
            ->when($storeId, function ($query) use ($storeId) {
                $query->where('sub_store_id', $storeId);
            })
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['opening_balance'] = round($stockIn->total_quantity - $stockOut->total_quantity, self::ROUND_PRE);
        $stock['opening_value'] = round($stockIn->stock_value - $stockOut->stock_value, self::ROUND_PRE);
        $stock['opening_rate'] = ($stock['opening_balance'] != 0) ? round(($stock['opening_value'] / $stock['opening_balance']), self::ROUND_PRE) : 0;
        $stock['opening_value'] = round($stock['opening_balance'] * $stock['opening_rate'], self::ROUND_PRE);

        // inwards balance section...
        $stockIn = $this->dyesStockIns()->where('trn_date', '>=', Carbon::parse($fromDate))
            ->where('trn_date', '<=', Carbon::parse($toDate))
            ->when($storeId == null, function ($query) use ($storeId) {
                $query->whereNull('sub_store_id');
            })
            ->when($storeId, function ($query) use ($storeId) {
                $query->where('sub_store_id', $storeId);
            })
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['inwards'] = round($stockIn->total_quantity, self::ROUND_PRE) ?? 0;
        $stock['inward_value'] = round($stockIn->stock_value, self::ROUND_PRE) ?? 0;
        $stock['inward_rate'] = ($stock['inwards'] != 0) ? round(($stock['inward_value'] / $stock['inwards']), self::ROUND_PRE) : 0;
        $stock['inward_value'] = round($stock['inwards'] * $stock['inward_rate'], self::ROUND_PRE);

        // outwards balance section...
        $stockOut = $this->dyesStockOuts()->where('trn_date', '>=', Carbon::parse($fromDate))
            ->where('trn_date', '<=', Carbon::parse($toDate))
            ->when($storeId == null, function ($query) use ($storeId) {
                $query->whereNull('sub_store_id');
            })
            ->when($storeId, function ($query) use ($storeId) {
                $query->where('sub_store_id', $storeId);
            })
            ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity'))
            ->first();
        $stock['outwards'] = round($stockOut->total_quantity, self::ROUND_PRE) ?? 0;
        $stock['outward_value'] = round($stockOut->stock_value, self::ROUND_PRE) ?? 0.00;
        $stock['outward_rate'] = ($stock['outwards'] != 0) ? round(($stock['outward_value'] / $stock['outwards']), self::ROUND_PRE) : 0;
        $stock['outward_value'] = round($stock['outwards'] * $stock['outward_rate'], self::ROUND_PRE);

        // closing balance section...
        $stock['closing_balance'] = round($stock['opening_balance'] + $stock['inwards'] - $stock['outwards'], self::ROUND_PRE);
        $stock['closing_value'] = round($stock['opening_value'] + $stock['inward_value'] - $stock['outward_value'], self::ROUND_PRE);
        $stock['closing_rate'] = ($stock['closing_balance'] != 0) ? round(($stock['closing_value'] / $stock['closing_balance']), self::ROUND_PRE) : 0;
        $stock['closing_value'] = round($stock['closing_balance'] * $stock['closing_rate'], self::ROUND_PRE);

        return $stock;
    }

    public function dyesLifeDateWiseStock($fromDate = null, $toDate = null, $storeId = null): array
    {
        $itemLifeEndDays = $this->dyesItemWiseLifeEndDays()->get(['life_end_days', 'trn_date']);

        $data = [];
        foreach ($itemLifeEndDays as $lifeEndDay) {
            $stock = [
                'opening_balance' => 0,
                'opening_rate' => 0,
                'opening_value' => 0,
                'inwards' => 0,
                'inward_rate' => 0,
                'inward_value' => 0,
                'outwards' => 0,
                'outward_rate' => 0,
                'outward_value' => 0,
                'closing_balance' => 0,
                'closing_rate' => 0,
                'closing_value' => 0,
                'trn_date' => '',
                'life_end_days' => 0,
            ];

            // Opening balance section...
            $stockIn = $this->dyesStockIns()->where('trn_date', '<=', Carbon::parse($fromDate)->subDay())
                ->where('life_end_days', $lifeEndDay->life_end_days)
                ->where('trn_date', $lifeEndDay->trn_date)
                ->when($storeId == null, function ($query) use ($storeId) {
                    $query->whereNull('sub_store_id');
                })
                ->when($storeId, function ($query) use ($storeId) {
                    $query->where('sub_store_id', $storeId);
                })
                ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity, trn_date, life_end_days'))
                ->first();
            $stockOut = $this->dyesStockOuts()->where('trn_date', '<=', Carbon::parse($fromDate)->subDay())
                ->where('life_end_days', $lifeEndDay->life_end_days)
                //                ->where('trn_date', $lifeEndDay->trn_date)
                ->when($storeId == null, function ($query) use ($storeId) {
                    $query->whereNull('sub_store_id');
                })
                ->when($storeId, function ($query) use ($storeId) {
                    $query->where('sub_store_id', $storeId);
                })
                ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity, trn_date, life_end_days'))
                ->first();
            $stock['opening_balance'] = round($stockIn->total_quantity - $stockOut->total_quantity, self::ROUND_PRE);
            $stock['opening_value'] = round($stockIn->stock_value - $stockOut->stock_value, self::ROUND_PRE);
            $stock['opening_rate'] = ($stock['opening_balance'] != 0) ? round(($stock['opening_value'] / $stock['opening_balance']), self::ROUND_PRE) : 0;
            $stock['opening_value'] = round($stock['opening_balance'] * $stock['opening_rate'], self::ROUND_PRE);

            $stock['trn_date'] = $stockIn->trn_date;

            // inwards balance section...
            $stockIn = $this->dyesStockIns()->where('trn_date', '>=', Carbon::parse($fromDate))
                ->where('trn_date', '<=', Carbon::parse($toDate))
                ->where('life_end_days', $lifeEndDay->life_end_days)
                ->where('trn_date', $lifeEndDay->trn_date)
                ->when($storeId == null, function ($query) use ($storeId) {
                    $query->whereNull('sub_store_id');
                })
                ->when($storeId, function ($query) use ($storeId) {
                    $query->where('sub_store_id', $storeId);
                })
                ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity, trn_date, life_end_days'))
                ->first();
            $stock['inwards'] = round($stockIn->total_quantity, self::ROUND_PRE) ?? 0;
            $stock['inward_value'] = round($stockIn->stock_value, self::ROUND_PRE) ?? 0;
            $stock['inward_rate'] = ($stock['inwards'] != 0) ? round(($stock['inward_value'] / $stock['inwards']), self::ROUND_PRE) : 0;
            $stock['inward_value'] = round($stock['inwards'] * $stock['inward_rate'], self::ROUND_PRE);

            $stock['trn_date'] = $stockIn->trn_date;

            // outwards balance section...
            $stockOut = $this->dyesStockOuts()->where('trn_date', '>=', Carbon::parse($fromDate))
                ->where('trn_date', '<=', Carbon::parse($toDate))
                ->where('life_end_days', $lifeEndDay->life_end_days)
                //                ->where('trn_date', $lifeEndDay->trn_date)
                ->when($storeId == null, function ($query) use ($storeId) {
                    $query->whereNull('sub_store_id');
                })
                ->when($storeId, function ($query) use ($storeId) {
                    $query->where('sub_store_id', $storeId);
                })
                ->select(DB::raw('sum(qty * rate) as stock_value, sum(qty) as total_quantity, trn_date, life_end_days'))
                ->first();
            $stock['outwards'] = round($stockOut->total_quantity, self::ROUND_PRE) ?? 0;
            $stock['outward_value'] = round($stockOut->stock_value, self::ROUND_PRE) ?? 0.00;
            $stock['outward_rate'] = ($stock['outwards'] != 0) ? round(($stock['outward_value'] / $stock['outwards']), self::ROUND_PRE) : 0;
            $stock['outward_value'] = round($stock['outwards'] * $stock['outward_rate'], self::ROUND_PRE);

            // closing balance section...
            $stock['closing_balance'] = round($stock['opening_balance'] + $stock['inwards'] - $stock['outwards'], self::ROUND_PRE);
            $stock['closing_value'] = round($stock['opening_value'] + $stock['inward_value'] - $stock['outward_value'], self::ROUND_PRE);
            $stock['closing_rate'] = ($stock['closing_balance'] != 0) ? round(($stock['closing_value'] / $stock['closing_balance']), self::ROUND_PRE) : 0;
            $stock['closing_value'] = round($stock['closing_balance'] * $stock['closing_rate'], self::ROUND_PRE);

            $stock['life_end_days'] = $lifeEndDay->life_end_days;

            $data[] = $stock;
        }

        return $data;
    }
}
