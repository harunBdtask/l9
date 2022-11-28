<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use App\FactoryIdTrait;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardColorSizeWiseIrisFabricSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardGeneralSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardGroupMarkerWiseSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardOnlySizeWiseSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardSimilarSizeAndSuffixSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardSizeWiseSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardStraightSerialHandler;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\BundleCardTotalPlyWiseSizeWiseSerialHandler;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;
use SkylarkSoft\GoRMG\SystemSettings\Models\Type;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class BundleCardGenerationDetail extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'bundle_card_generation_details';

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
        'size_suffix_sl_status',
        'cons_result',
        'is_cons_approved',
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['bundleCardsDelete', 'bundleCardGenerationCache'];

    public $poBreakDown = [];
    public $actualBundleQty = 0;
    public $leftQty = 0;

    const BUNDLE_CARD_SERIAL_OPTIONS = [
        '0' => 'Default', // PN
        '1' => 'Size and Suffix Wise Serial', // Unigears
        '2' => 'Total Ply and Size Wise Serial', // Unigears
        '3' => 'Ratio Wise Serial', // Mondol, APS, Saturn (Ratio Wise)
        '4' => 'Only Size Wise Serial', // RS Knit
        '5' => 'Group Marker(No)', // GMS
        '6' => 'Color Size Wise Group-Iris Fabric', // Iris Fabric
        '7' => 'Group Marker(Yes)', // GMS
        '8' => 'Straight Serial', // Arabela Avant
    ];

    const DEFAULT_SERIAL = 0;
    const SIZE_SUFFIX_WISE_SERIAL = 1;
    const TOTAL_PLY_AND_SIZE_WISE_SERIAL = 2;
    const RATIO_WISE_SERIAL = 3;
    const ONLY_SIZE_WISE_SERIAL = 4;
    const GROUP_MARKER_WISE_SERIAL_NO = 5;
    const GROUP_MARKER_WISE_SERIAL_YES = 7;
    const COLOR_SIZE_WISE_GROUP_IRIS_FABRIC = 6;
    const STRAIGHT_SERIAL = 8;
    const ACCEPTED_SERIAL_OPTIONS = [self::DEFAULT_SERIAL, self::COLOR_SIZE_WISE_GROUP_IRIS_FABRIC, self::SIZE_SUFFIX_WISE_SERIAL];
    const AUTO_BUNDLE_CARD_STATUS = 0;
    const MANUAL_BUNDLE_CARD_STATUS = 1;

    public static function getBundleGenerationListQuery($isManual = 0, $q = null)
    {
        return self::query()
            ->leftJoin('bundle_cards', 'bundle_cards.bundle_card_generation_detail_id', 'bundle_card_generation_details.sid')
            ->leftJoin('buyers', 'buyers.id', 'bundle_card_generation_details.buyer_id')
            ->leftJoin('orders', 'orders.id', 'bundle_card_generation_details.order_id')
            ->leftJoin('purchase_orders', 'purchase_orders.id', 'bundle_cards.purchase_order_id')
            ->leftJoin('parts', 'parts.id', 'bundle_card_generation_details.part_id')
            ->leftJoin('users', 'users.id', 'bundle_card_generation_details.created_by')
            ->where('bundle_card_generation_details.is_manual', $isManual)
            ->where(function ($query) use ($q) {
                $query->orWhere('bundle_card_generation_details.sid', $q)
                    ->orWhere('buyers.name', $q)
                    ->orWhere('orders.style_name', 'like', '%' . $q . '%')
                    ->orWhere('orders.reference_no', 'like', '%' . $q . '%')
                    ->orWhere('parts.name', $q);
            })
            ->selectRaw("
                bundle_card_generation_details.id as id, 
                bundle_card_generation_details.sid as sid, 
                buyers.name as buyer_name,
                orders.style_name as style_name,
                purchase_orders.po_no as po_no,
                parts.name as part_name,
                bundle_card_generation_details.max_quantity as max_quantity,
                bundle_card_generation_details.created_at as created_at,
                count(bundle_cards.id) as bundles_count,
                sum(bundle_cards.quantity) as bundles_quantity,
                users.first_name as first_name,
                users.last_name as last_name
            ")
            ->groupBy(
                'bundle_card_generation_details.id',
                'bundle_card_generation_details.sid',
                'bundle_card_generation_details.buyer_id',
                'bundle_card_generation_details.order_id',
                'bundle_cards.purchase_order_id',
                // 'bundle_card_generation_details.part_id',
                'bundle_card_generation_details.created_by'
            )
            ->orderBy('bundle_card_generation_details.sid', 'desc');
    }

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
            'total_gsm' => 0,
            'average_dia' => 0,
            'avarage_gsm' => 0,
        ];

        foreach ($rolls as $roll) {
            $summary['total_ply'] += $roll['plys'];
            $summary['total_weight'] += $roll['weight'];
            $summary['total_dia'] += $roll['dia'];
            $summary['total_gsm'] += $roll['gsm'] ?? 0;
        }

        $summary['average_dia'] = $summary['total_dia'] / count($rolls);
        $summary['average_gsm'] = $summary['total_gsm'] / count($rolls);
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

    public function getBundleSummaryAttribute()
    {
        $size_suffix_sl_status = $this->size_suffix_sl_status;
        switch ($size_suffix_sl_status) {
            case 1:
                $bundleSummary = (new BundleCardSimilarSizeAndSuffixSerialHandler($this, false))->handle();
                break;
            case 2:
                $bundleSummary = (new BundleCardTotalPlyWiseSizeWiseSerialHandler($this, false))->handle();
                break;
            case 3:
                $bundleSummary = (new BundleCardSizeWiseSerialHandler($this, false))->handle();
                break;
            case 4:
                $bundleSummary = (new BundleCardOnlySizeWiseSerialHandler($this, false))->handle();
                break;
            case 5:
                $bundleSummary = (new BundleCardGroupMarkerWiseSerialHandler($this, false))->handle();
                break;
            case 6:
                $bundleSummary = (new BundleCardColorSizeWiseIrisFabricSerialHandler($this, false))->handle();
                break;
            case 7:
                $bundleSummary = (new BundleCardGroupMarkerWiseSerialHandler($this, false, true))->handle();
                break;
            case 8:
                $bundleSummary = (new BundleCardStraightSerialHandler($this, false))->handle();
                break;
            default:
                $bundleSummary = (new BundleCardGeneralSerialHandler($this, false))->handle();
                break;
        }

        return $bundleSummary;
    }

    public function getBundleSummaryWithoutScopeAttribute()
    {
        $size_suffix_sl_status = $this->size_suffix_sl_status;
        switch ($size_suffix_sl_status) {
            case 1:
                $bundleSummary = (new BundleCardSimilarSizeAndSuffixSerialHandler($this, true))->handle();
                break;
            case 2:
                $bundleSummary = (new BundleCardTotalPlyWiseSizeWiseSerialHandler($this, true))->handle();
                break;
            case 3:
                $bundleSummary = (new BundleCardSizeWiseSerialHandler($this, true))->handle();
                break;
            case 4:
                $bundleSummary = (new BundleCardOnlySizeWiseSerialHandler($this, true))->handle();
                break;
            case 5:
                $bundleSummary = (new BundleCardGroupMarkerWiseSerialHandler($this, true))->handle();
                break;
            case 6:
                $bundleSummary = (new BundleCardColorSizeWiseIrisFabricSerialHandler($this, true))->handle();
                break;
            case 7:
                $bundleSummary = (new BundleCardGroupMarkerWiseSerialHandler($this, true, true))->handle();
                break;
            case 8:
                $bundleSummary = (new BundleCardStraightSerialHandler($this, true))->handle();
                break;
            default:
                $bundleSummary = (new BundleCardGeneralSerialHandler($this, true))->handle();
                break;
        }
        return $bundleSummary;
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

    private function getPlySerial($startingSerial, $ratio, $plyNo, $plys, $slIncremental = null)
    {
        $tube = $this->is_tube + 1;
        if ($this->size_suffix_sl_status == self::COLOR_SIZE_WISE_GROUP_IRIS_FABRIC && $this->is_tube == 1) {
            $slStart = $startingSerial[$ratio['size_name']] + $plyNo + 1;
            $slEnd = $startingSerial[$ratio['size_name']] + $plyNo + $plys;
            $plySerial = $slStart . '-' . $slEnd;
        } else {
            $slStart = $startingSerial[$ratio['size_name']] + ($plyNo * $tube) + 1;
            $slEnd = $startingSerial[$ratio['size_name']] + ($plyNo + $plys) * $tube;

            $plySerial = $slStart . '-' . $slEnd;
            $totalPly = $this->roll_summary['total_ply'];

            if ($this->size_suffix_sl_status == self::GROUP_MARKER_WISE_SERIAL_YES && isset($slIncremental)) {
                $plySerials = [];
                $slStart = $startingSerial[$ratio['size_name']];

                for ($i = 0; $i < $ratio['ratio']; $i++) {
                    $plySerials[] = ($slStart + ($plyNo * $tube) + 1) . '-' . ($slStart + ($plyNo + $plys) * $tube);
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
        }

        return $plySerial;
    }

    public function generateBundleCards($bundleNo, $sizeWiseBundleNo, $plyNo, $ratio, $roll, $startingSerial, $slIncremental = null)
    {
        $bundleCards = [];

        $sizeId = $ratio['size_id'];
        $lotInfo = $this->getLotInfoForRollNo($roll['roll_no']);
        $colorId = $lotInfo && array_key_exists('lot_id', $lotInfo) && $this->used_lots ? $this->used_lots->where('id', $lotInfo['lot_id'])->first()->color_id : 0;
        $cuttingNo = $this->findCuttingNo($colorId);

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

            $plySerial = $this->getPlySerial($startingSerial, $ratio, $plyNo, $plys, $slIncremental);

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

        $lots = $this->used_lots->whereIn('id', $lotIds)->unique('id')->values();
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
        return $this->hasMany(BundleCard::class, 'bundle_card_generation_detail_id', 'id');
    }

    public function bundleCardGenerationCache()
    {
        return $this->hasOne(BundleCardGenerationCache::class, 'bg_id', 'id');
    }

    public function bundleCards()
    {
        return $this->hasMany(BundleCard::class, 'bundle_card_generation_detail_id', 'sid');
    }

    public function bundleCardsWithoutGlobalScopes()
    {
        return $this->hasMany(BundleCard::class, 'bundle_card_generation_detail_id', 'sid')->withoutGlobalScope('factoryId');
    }

    public function bundleCardsGetColors()
    {
        return $this->hasMany(BundleCard::class, 'bundle_card_generation_detail_id', 'sid');
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

    public function purchaseOrderQtys()
    {
        return $this->hasOneThrough(PurchaseOrder::class, BundleCard::class, 'bundle_card_generation_detail_id', 'id', 'sid', 'purchase_order_id');
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
