<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\FactoryIdTrait;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardGeneralSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardGroupMarkerWiseSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardOnlySizeWiseSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardSimilarSizeAndSuffixSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardSizeWiseSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardTotalPlyWiseSizeWiseSerialHandler;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;
use SkylarkSoft\GoRMG\SystemSettings\Models\Type;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ArchivedBundleCardGenerationDetail extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'archived_bundle_card_generation_details';

    protected $fillable = [
        'sid',
        'is_regenerated',
        'max_quantity',
        'booking_consumption',
        'booking_dia',
        'booking_gsm',
        'cons_validation',
        'buyer_id',
        'order_id',
        'garments_item_id',
        'colors',
        'cutting_no',
        'cutting_floor_id',
        'cutting_table_id',
        'is_tube',
        'part_id',
        'type_id',
        'lot_ranges',
        'rolls',
        'ratios',
        'is_manual',
        'po_details',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
        'size_suffix_sl_status'
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['bundleCardsDelete', 'bundleCardGenerationCache'];

    public $poBreakDown = [];
    public $actualBundleQty = 0;
    public $leftQty = 0;

    const BUNDLE_CARD_SERIAL_OPTIONS = [
        '0' => 'Default', // Iris Fabric
        '1' => 'Size and Suffix Wise Serial', // Unigears
        '2' => 'Total Ply and Size Wise Serial', // Unigears
        '3' => 'Size Wise Serial', // Mondol, APS, Saturn
        '4' => 'Only Size Wise Serial', // RS Knit
        '5' => 'Group Marker Wise Serial' // GMS
    ];

    const DEFAULT_SERIAL = 0;
    const SIZE_SUFFIX_WISE_SERIAL = 1;
    const TOTAL_PLY_AND_SIZE_WISE_SERIAL = 2;
    const RATIO_WISE_SERIAL = 3;
    const ONLY_SIZE_WISE_SERIAL = 4;
    const GROUP_MARKER_WISE_SERIAL = 5;
    const ACCEPTED_SERIAL_OPTIONS = [self::DEFAULT_SERIAL, self::SIZE_SUFFIX_WISE_SERIAL];

    public function getAllColorsAttribute()
    {
        $colorIds = $this->bundleCards->unique('color_id')->values()->pluck('color_id')->all();
        $colors = Color::whereIn('id', $colorIds)->get();

        return $colors->implode('name', ', ');
    }

    public function getAllColorsWithoutScopeAttribute()
    {
        $colorIds = $this->bundleCardsWithoutGlobalScopes->unique('color_id')->values()->pluck('color_id')->all();
        $colors = Color::withoutGlobalScope('factoryId')->whereIn('id', $colorIds)->get();

        return $colors->implode('name', ', ');
    }

    public function setLotRangesAttribute($value)
    {
        $this->attributes['lot_ranges'] = json_encode($value);
    }

    public function getLotRangesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRollsAttribute($value)
    {
        $this->attributes['rolls'] = json_encode($value);
    }

    public function getRollsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRatiosAttribute($value)
    {
        $this->attributes['ratios'] = json_encode($value);
    }

    public function getRatiosAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPoDetailsAttribute($value)
    {
        $this->attributes['po_details'] = json_encode($value);
    }

    public function getPoDetailsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getRollSummaryAttribute()
    {
        $rolls = $this->rolls;
        $summary = [
            'total_ply' => 0,
            'total_weight' => 0,
            'total_dia' => 0,
            'average_dia' => 0,
        ];

        foreach ($rolls as $roll) {
            $summary['total_ply'] += $roll['plys'];
            $summary['total_weight'] += $roll['weight'];
            $summary['total_dia'] += $roll['dia'];
        }

        $summary['total_weight'] = $summary['total_weight'];
        $summary['total_dia'] = $summary['total_dia'];
        $summary['average_dia'] = $summary['total_dia'] / count($rolls);
        $summary['total_roll'] = count($rolls);

        return $summary;
    }

    public function getTotalCuttingQuantityShouldBeAttribute()
    {
        return round(($this->roll_summary['total_weight'] * 12) / $this->booking_consumption);
    }

    public function getUsedConsumptionAttribute()
    {
        return (($this->roll_summary['total_weight'] * 12) / $this->bundle_summary['total_quantity']);
    }

    public function getUsedConsumptionWithoutScopeAttribute()
    {
        return (($this->roll_summary['total_weight'] * 12) / $this->bundle_summary_without_scope['total_quantity']);
    }

    public function getQuantitySaveOrLossAttribute()
    {
        return ($this->bundle_summary['total_quantity'] - $this->total_cutting_quantity_should_be);
    }

    public function getQuantitySaveOrLossWithoutScopeAttribute()
    {
        return ($this->bundle_summary_without_scope['total_quantity'] - $this->total_cutting_quantity_should_be);
    }

    public function getConsumptionSaveOrLossAttribute()
    {
        return ($this->booking_consumption - $this->used_consumption);
    }

    public function getConsumptionSaveOrLossWithoutScopeAttribute()
    {
        return ($this->booking_consumption - $this->used_consumption_without_scope);
    }

    public function getFabricSaveAttribute()
    {
        return (($this->quantity_save_or_loss / 12) * $this->booking_consumption);
    }

    public function getFabricSaveWithoutScopeAttribute()
    {
        return (($this->quantity_save_or_loss_without_scope / 12) * $this->booking_consumption);
    }

    public function getResultAttribute()
    {
        return $this->quantity_save_or_loss >= 0;
    }

    public function getResultWithoutScopeAttribute()
    {
        return $this->quantity_save_or_loss_without_scope >= 0;
    }

    public function getMarkerPieceAttribute()
    {
        $markerPiece = 0;

        foreach ($this->ratios as $ratio) {
            $markerPiece += $ratio['ratio'];
        }

        return $markerPiece;
    }

    public function getPlyNo($sizeId, $suffix = null)
    {
        $ratios = collect($this->ratios)->filter(function ($item) use ($sizeId) {
            return $item['size_id'] == $sizeId;
        });

        $totalPlys = collect($this->rolls)->sum('plys');

        $key = 0;
        foreach ($ratios as $ratio) {
            if ($ratio['size_id'] == $sizeId && $ratio['suffix'] == $suffix) {
                return $totalPlys * $key;
            }

            $key++;
        }

        return 0;
    }

    public function generateBundleCards($bundleNo, $sizeWiseBundleNo, $plyNo, $ratio, $roll, $startingSerial, $slIncremental = null)
    {
        $bundleCards = [];

        $sizeCode = $ratio['size_code'];
        $sizeId = $ratio['size_id'];
        $lotInfo = $this->getLotInfoForRollNo($roll['roll_no']);
        $colorId = Lot::findOrFail($lotInfo['lot_id'])->color_id;
        $cuttingNo = $this->findCuttingNo($colorId);

        $tube = $this->is_tube + 1;
        $remainingPly = $roll['plys'];
        $remainingQty = $remainingPly * $ratio['ratio'];

        while ($remainingPly) {
            $plys = $remainingPly;
            $maxBundleQty = $this->getMaxBundleQty($colorId, $sizeId, $ratio['ratio']);

            while (($plys * $ratio['ratio']) > $maxBundleQty) $plys--;

            $qty = $plys * $ratio['ratio'];

            if ($this->leftQty) {
                $qty = $qty - $ratio['ratio'] + $this->leftQty;
            }

            $qty = $qty < $remainingQty ? $qty : $remainingQty;
            $po = $this->definePo($colorId, $sizeId, $qty);
            $country_id = $this->getCountryId($po['purchase_order_id'], $colorId, $sizeId);

            $slStart = $startingSerial[$ratio['size_name']] + ($plyNo * $tube) + 1;
            $slEnd = $startingSerial[$ratio['size_name']] + ($plyNo + $plys) * $tube;

            $plySerial = $slStart . '-' . $slEnd;
            $totalPly = $this->roll_summary['total_ply'];

            if ($this->size_suffix_sl_status == self::GROUP_MARKER_WISE_SERIAL && isset($slIncremental)) {
                $plySerials = [];
                $slStart = $startingSerial[$ratio['size_name']];

                for ($i = 0; $i < $ratio['ratio'] ; $i++) {
                    $plySerials[] = ($slStart + ($plyNo * $tube) + 1).'-'.($slStart + ($plyNo + $plys) * $tube);
                    $slStart += $slIncremental;
                }

                $plySerial = implode('/', $plySerials);
            }
            if (in_array($this->size_suffix_sl_status, self::ACCEPTED_SERIAL_OPTIONS)) {
                $plyDiff = $slEnd - $slStart;
                for ($i = 1; $i < $ratio['ratio']; $i++) {
                    $st = ($i * $totalPly) + $slStart;
                    $plySerial .= '/' . $st . '-' . ($st + $plyDiff);
                }
            }

            $bundleCards[] = [
                'bundle_no' => $bundleNo,
                'size_wise_bundle_no' => $sizeWiseBundleNo,
                'quantity' => $this->actualBundleQty,
                'lot_id' => $lotInfo['lot_id'],
                'color_id' => $colorId,
                'size_id' => $sizeId,
                'suffix' => $ratio['suffix'],
                'serial' => $plySerial,
                'roll_no' => $roll['roll_no'],
                'bundle_card_generation_detail_id' => $this->id,
                'factory_id' => $this->factory_id,
                'cutting_no' => $cuttingNo,
                'purchase_order_id' => $po['purchase_order_id'] ?? null,
                'buyer_id' => $this->buyer_id,
                'order_id' => $this->order_id,
                'garments_item_id' => $this->garments_item_id,
                'country_id' => $country_id ?? null,
                'status' => 0,
                'cutting_challan_status' => 0,
                'cutting_qc_challan_status' => 0,
                'cutting_table_id' => $this->cutting_table_id,
                'cutting_floor_id' => $this->cutting_floor_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'sl_overflow' => $this->leftQty ? 1 : 0,
            ];

            $remainingPly -= $plys;
            $remainingQty -= $this->actualBundleQty;
            $plyNo += $plys;
            $bundleNo++;
            $sizeWiseBundleNo++;

            if ($this->leftQty) {
                $remainingPly += 1;
                $plyNo = $plyNo - 1;
            }
        }

        return $bundleCards;
    }

    private function getCountryId($purchaseOrderId = "", $colorId, $sizeId)
    {
        $countryId = null;
        if ($purchaseOrderId == "") {
            return $countryId;
        }

        foreach ($this->poBreakDown as $key => $po) {

            if ($po['purchase_order_id'] == $purchaseOrderId && $po['color_id'] == $colorId && $po['size_id'] == $sizeId) {
                $countryId = array_key_exists('country_id', $po) ? $po['country_id'] : null;

                break;
            }
        }
        return $countryId;
    }

    private function getMaxBundleQty($colorId, $sizeId, $ratio)
    {
        $maxBundleQty = $this->max_quantity;
        $poQty = $maxBundleQty;

        foreach ($this->poBreakDown as $po) {
            if ($po['color_id'] == $colorId && $po['size_id'] == $sizeId && $po['quantity'] > 0) {
                $poQty = $po['quantity'];

                break;
            }
        }

        if ($poQty < $maxBundleQty) {
            $maxBundleQty = $poQty + ($poQty % $ratio);
        }

        if ($maxBundleQty < $ratio) {
            $maxBundleQty = $ratio;
        }

        return $maxBundleQty;
    }

    private function definePo($colorId, $sizeId, $qty)
    {
        foreach ($this->poBreakDown as $key => $po) {
            if ($po['quantity'] <= 0) {
                continue;
            }

            if ($po['color_id'] == $colorId && $po['size_id'] == $sizeId && $po['quantity'] >= $qty) {
                $this->poBreakDown[$key]['quantity'] -= $qty;
                $this->actualBundleQty = $qty;
                $this->leftQty = 0;

                break;
            } elseif ($po['color_id'] == $colorId && $po['size_id'] == $sizeId && $po['quantity'] < $qty) {
                $this->actualBundleQty = $this->poBreakDown[$key]['quantity'];
                $this->leftQty = $qty - $this->actualBundleQty;
                $this->poBreakDown[$key]['quantity'] = 0;

                break;
            }
        }

        return $this->poBreakDown[$key];
    }


    private function plysByColor()
    {
        $plysByColor = [];
        $lotIds = [];
        foreach ($this->lot_ranges as $lot) {
            $lotIds[] = $lot['lot_id'];
        }

        $lots = Lot::whereIn('id', $lotIds)->get()->unique('id')->values();
        foreach ($lots as $lot) {
            $plysByColor[$lot->color_id] = 0;
        }

        foreach ($this->rolls as $roll) {
            foreach ($this->lot_ranges as $lot) {
                if ($roll['roll_no'] >= $lot['from'] && $roll['roll_no'] <= $lot['to']) {
                    $lot = $lots->where('id', $lot['lot_id'])->first();
                    $plysByColor[$lot->color_id] += $roll['plys'];
                }
            }
        }

        return $plysByColor;
    }

    private function setSlStart($plysByColor = [])
    {
        foreach ($plysByColor as $colorId => $plys) {
            if (!array_key_exists($colorId, $this->slStart)) {
                $this->slStart[$colorId] = 0;
            }

            if (($this->slStart[$colorId] + ($plysByColor[$colorId] * $this->marker_piece)) > 10000) {
                $this->slStart[$colorId] = 0;
            }
        }
    }

    private function getLotInfoForRollNo($rollNo)
    {
        $lotRanges = $this->lot_ranges;

        foreach ($lotRanges as $lotRange) {
            if ($rollNo >= $lotRange['from'] && $rollNo <= $lotRange['to']) {
                return [
                    'lot_id' => $lotRange['lot_id'],
                    'lot_no' => $lotRange['lot_no'],
                    'lot_code' => $lotRange['lot_code'],
                ];
            }
        }

        if (count($lotRanges) == 1) {
            return [
                'lot_id' => $lotRanges[0]['lot_id'],
                'lot_no' => $lotRanges[0]['lot_no'],
                'lot_code' => $lotRanges[0]['lot_code']
            ];
        }

        return null;
    }

    private function findCuttingNo($colorId)
    {
        $cuttingNosWithColor = explode('; ', $this->cutting_no);
        $cuttingNos = [];

        foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
            $cutting = explode(': ', $cuttingNoWithColor);

            if (count($cutting) == 2) {
                $cuttingNos[$cutting[0]] = $cutting[1];
            }
        }

        if (array_key_exists($colorId, $cuttingNos)) {
            return $cuttingNos[$colorId];
        }

        return 1;
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function buyerWithoutGlobalScope()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withoutGlobalScope('factoryId');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function orderWithoutGlobalScope()
    {
        return $this->belongsTo(Order::class, 'order_id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id')->withDefault();
    }

    public function partWithoutGlobalScope()
    {
        return $this->belongsTo(Part::class, 'part_id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id')->withDefault();
    }

    public function typeWithoutGlobalScope()
    {
        return $this->belongsTo(Type::class, 'type_id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function bundleCardsDelete()
    {
        return $this->hasMany(ArchivedBundleCard::class, 'bundle_card_generation_detail_id', 'id');
    }

    public function bundleCardGenerationCache()
    {
        return $this->hasOne(BundleCardGenerationCache::class, 'bg_id', 'id');
    }

    public function bundleCards()
    {
        return $this->hasMany(ArchivedBundleCard::class, 'bundle_card_generation_detail_id', 'sid');
    }

    public function bundleCardsWithoutGlobalScopes()
    {
        return $this->hasMany(ArchivedBundleCard::class, 'bundle_card_generation_detail_id', 'sid')->withoutGlobalScope('factoryId');
    }

    public function bundleCardsGetColors()
    {
        return $this->hasMany(ArchivedBundleCard::class, 'bundle_card_generation_detail_id', 'sid');
    }

    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'bundle_cards', 'bundle_card_generation_detail_id', 'purchase_order_id')
            ->withoutGlobalScopes()
            ->distinct('id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function cuttingFloor()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id', 'id')->withDefault();
    }

    public function cuttingFloorWithoutGlobalScope()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id', 'id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function cuttingTable()
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id', 'id')->withDefault();
    }

    public function cuttingTableWithoutGlobalScope()
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id', 'id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id', 'purchase_order_id');
    }

    public static function getCuttingNo($buyer_id, $order_id, $color_id)
    {
        $data = self::where(['buyer_id' => $buyer_id, 'order_id' => $order_id, 'color_id' => $color_id])
            ->get()->groupBy('cutting_no');

        $result = [];
        foreach ($data as $key => $cn_no) {
            $value = $cn_no->first();
            $result[$value->id] = $value->cutting_no;
        }

        return $result;
    }
}
