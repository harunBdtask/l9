<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\FactoryIdTrait;
use Carbon\Carbon;

class BundleCardGenerationView extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'bundle_card_generation_details_view';

    protected $fillable = [
        'sid',
        'is_regenerated',
        'max_quantity',
        'booking_consumption',
        'booking_dia',
        'buyer_id',
        'style_id',
        'order_id',
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
        'factory_id'
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['bundleCardsDelete'];

    public $poBreakDown = [];
    public $actualBundleQty = 0;
    public $leftQty = 0;

    public static function getBundleGenerationListQuery($isManual = 0)
    {
        return self::query()
            ->leftJoin('bundle_cards', 'bundle_cards.bundle_card_generation_detail_id', 'bundle_card_generation_details_view.sid')
            ->leftJoin('buyers', 'buyers.id', 'bundle_card_generation_details_view.buyer_id')
            ->leftJoin('orders', 'orders.id', 'bundle_card_generation_details_view.order_id')
            ->leftJoin('purchase_orders', 'purchase_orders.id', 'bundle_cards.purchase_order_id')
            ->leftJoin('parts', 'parts.id', 'bundle_card_generation_details_view.part_id')
            ->leftJoin('users', 'users.id', 'bundle_card_generation_details_view.created_by')
            ->where('bundle_card_generation_details_view.is_manual', $isManual)
            ->selectRaw("
                bundle_card_generation_details_view.id as id, 
                bundle_card_generation_details_view.sid as sid, 
                buyers.name as buyer_name,
                orders.style_name as style_name,
                purchase_orders.po_no as po_no,
                parts.name as part_name,
                bundle_card_generation_details_view.max_quantity as max_quantity,
                bundle_card_generation_details_view.created_at as created_at,
                count(bundle_cards.id) as bundles_count,
                sum(bundle_cards.quantity) as bundles_quantity,
                users.first_name as first_name,
                users.last_name as last_name
            ")
            ->groupBy(
                'bundle_card_generation_details_view.id',
                'bundle_card_generation_details_view.sid',
                'bundle_card_generation_details_view.buyer_id',
                'bundle_card_generation_details_view.order_id',
                'bundle_cards.purchase_order_id',
                // 'bundle_card_generation_details_view.part_id',
                'bundle_card_generation_details_view.created_by'
            )
            ->orderBy('bundle_card_generation_details_view.sid', 'desc');
    }
    
    public function getAllColorsAttribute()
    {
        $colorIds = $this->bundleCards->unique('color_id')->values()->pluck('color_id')->all();
        $colors = Color::whereIn('id', $colorIds)->get();

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
        return ($this->roll_summary['total_weight'] * 12) / $this->booking_consumption;
    }

    public function getUsedConsumptionAttribute()
    {
        return (($this->roll_summary['total_weight'] * 12) / $this->bundle_summary['total_quantity']);
    }

    public function getQuantitySaveOrLossAttribute()
    {
        return ($this->bundle_summary['total_quantity'] - $this->total_cutting_quantity_should_be);
    }

    public function getConsumptionSaveOrLossAttribute()
    {
        return ($this->booking_consumption - $this->used_consumption);
    }

    public function getFabricSaveAttribute()
    {
        return (($this->quantity_save_or_loss / 12) * $this->booking_consumption);
    }

    public function getResultAttribute()
    {
        return $this->quantity_save_or_loss > 0;
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
        $ratios = $this->ratios;
        $rolls = $this->rolls;
        $this->poBreakDown = $this->po_details ?? [];
        $maxBundleQty = $this->max_quantity;
        $persistentBundleCards = $this->bundleCards;
        $tube = $this->is_tube + 1;

        $bundleSummary = [
            'bundle' => [],
            'bundle_cards' => [],
            'total_bundle' => 0,
            'total_quantity' => 0,
        ];

        $sizeWiseBundleNos = [];
        foreach ($ratios as $ratio) {
            $sizeWiseBundleNos[$ratio['size_name']] = 1;
        }

        foreach ($ratios as $ratio) {
            $summary = [
                'serial' => $ratio['serial_no'],
                'size' => $ratio['size_name'],
                'suffix' => $ratio['suffix'],
                'bundles' => 0,
                'quantity' => 0
            ];
            $plyNo = 0;

            foreach ($rolls as $roll) {
                $qty = $roll['plys'] * $ratio['ratio'];
                $bundleNo = $bundleSummary['total_bundle'] + 1;
                $sizeWiseBundleNo = $sizeWiseBundleNos[$ratio['size_name']];

                if ($persistentBundleCards->isEmpty()) {
                    $bundleCards = $this->generateBundleCards($bundleNo, $sizeWiseBundleNo, $plyNo, $ratio, $roll);
                    $bundlesPerRoll = count($bundleCards);

                    $sizeWiseBundleNos[$ratio['size_name']] += $bundlesPerRoll;
                    $summary['bundles'] += $bundlesPerRoll;
                    $bundleSummary['total_bundle'] += $bundlesPerRoll;

                    array_push($bundleSummary['bundle_cards'], ...$bundleCards);

                    $summary['quantity'] += $qty;
                    $plyNo += $roll['plys'];
                }
            }

            if (!$persistentBundleCards->isEmpty()) {
                $bundleCards = $persistentBundleCards
                    ->where('size_id', $ratio['size_id'])
                    ->where('suffix', $ratio['suffix']);

                $summary['bundles'] = $bundleCards->count();
                $bundleSummary['total_bundle'] += $summary['bundles'];

                if ($bundleCards->count()) {
                    array_push($bundleSummary['bundle_cards'], ...$bundleCards->toArray());
                }

                $summary['quantity'] = $bundleCards->sum('quantity');
            }

            $bundleSummary['bundle'][] = $summary;
            $bundleSummary['total_quantity'] += $summary['quantity'];
        }

        return $bundleSummary;
    }

    public function generateBundleCards($bundleNo, $sizeWiseBundleNo, $plyNo, $ratio, $roll)
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

            $slStart = ($plyNo * $tube) + 1;
            $slEnd = ($plyNo + $plys) * $tube;

            $plySerial = $slStart . '-' . $slEnd;

            //$forTubeOrNot = ($this->is_tube == 1) ? 2 : 1; 

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
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function part()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Part', 'part_id')->withDefault();
    }

    public function type()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Type', 'type_id')->withDefault();
    }

    public function bundleCardsDelete()
    {
        return $this->hasMany(BundleCard::class, 'bundle_card_generation_detail_id', 'id');
    }

    public function bundleCards()
    {
        return $this->hasMany(BundleCard::class, 'bundle_card_generation_detail_id', 'sid');
    }

    public function bundleCardsGetColors()
    {
        return $this->hasMany(BundleCard::class, 'bundle_card_generation_detail_id', 'sid');
    }

    public function purchaseOrders()
    {
        return $this->belongsToMany('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'bundle_cards', 'bundle_card_generation_detail_id', 'purchase_order_id')
            ->withoutGlobalScopes()
            ->distinct('id');
    }

    public function createdBy()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\User', 'created_by')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id')->withDefault();
    }

    public function cuttingTable()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable', 'cutting_table_id', 'id')->withDefault();
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail', 'purchase_order_id', 'purchase_order_id');
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
