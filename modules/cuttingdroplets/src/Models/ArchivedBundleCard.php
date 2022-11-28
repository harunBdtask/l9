<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ArchivedBundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintDeliveryInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Washingdroplets\Models\Washing;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class ArchivedBundleCard extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'archived_bundle_cards';

    protected $fillable = [
        'bundle_no',
        'size_wise_bundle_no',
        'quantity',
        'buyer_id',
        'order_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'country_id',
        'lot_id',
        'roll_no',
        'size_id',
        'suffix',
        'serial',
        'sl_overflow',
        'cutting_no',
        'cutting_challan_no',
        'cutting_challan_status',
        'cutting_qc_challan_no',
        'cutting_qc_challan_status',
        'bundle_card_generation_detail_id',
        'replace',
        'fabric_holes_small',
        'fabric_holes_large',
        'end_out',
        'dirty_spot',
        'oil_spot',
        'colour_spot',
        'lycra_missing',
        'missing_yarn',
        'yarn_contamination',
        'crease_mark',
        'others',
        'total_rejection',
        'production_rejection_qty',
        'qc_rejection_qty',
        'print_rejection',
        'embroidary_rejection',
        'sewing_rejection',
        'washing_rejection',
        'print_factory_receive_rejection',
        'print_factory_delivery_rejection',
        'status',
        'qc_status',
        'cutting_date',
        'print_embr_send_scan_time',
        'print_sent_date',
        'print_embr_received_scan_time',
        'print_received_date',
        'embroidary_sent_date',
        'embroidary_received_date',
        'input_scan_time',
        'input_date',
        'sewing_output_date',
        'washing_date',
        'cutting_table_id',
        'cutting_floor_id',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function getScanableRpBarcodeAttribute()
    {
        return '1' . str_pad($this->attributes['id'], 8, '0', STR_PAD_LEFT);
    }

    public function getScanableOpBarcodeAttribute()
    {
        return str_pad($this->attributes['id'], 9, '0', STR_PAD_LEFT);
    }

    public function getSizeNameAttribute()
    {
        return $this->suffix ? $this->size->name . ' (' . $this->suffix . ')' : $this->size->name;
    }

    public function details()
    {
        return $this->belongsTo(ArchivedBundleCardGenerationDetail::class, 'bundle_card_generation_detail_id')->withDefault();
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function cuttingTable()
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id')->withDefault();
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class, 'lot_id')->withDefault();
    }

    public function lotWithoutScope()
    {
        return $this->belongsTo(Lot::class, 'lot_id')->withDefault()->withoutGlobalScope('factoryId');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id')->withDefault();
    }

    public function cuttingFloor()
    {
        return $this->belongsTo(CuttingFloor::class, 'floor_id')->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id')->withDefault();
    }

    public function cutting_inventory()
    {
        return $this->hasOne(CuttingInventory::class);
    }

    public function print_inventory()
    {
        return $this->hasOne(PrintInventory::class);
    }

    public function print_production()
    {
        return $this->hasOne(PrintReceiveInventory::class, 'bundle_card_id');
    }

    public function print_receive_inventory()
    {
        return $this->hasOne(PrintReceiveInventory::class);
    }

    public function print_embr_qc_inventory()
    {
        return $this->hasOne(PrintEmbroideryQcInventory::class);
    }

    public function print_delivery_inventory()
    {
        return $this->hasOne(PrintDeliveryInventory::class);
    }

    public function sewingoutput()
    {
        return $this->hasOne(Sewingoutput::class);
    }

    public function washing()
    {
        return $this->hasOne(Washing::class);
    }

    public static function getCuttingNo($purchase_order_id, $color_id)
    {
        $data = self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id'          => $color_id
        ])->get()->groupBy('cutting_no');

        $result = [];
        foreach ($data as $key => $cn_no) {
            $value = $cn_no->first();
            $result[$value->id] = $value->cutting_no;
        }
        return $result;
    }

    // get maximum rejection from print and embroidary rejection
    public function getPrintEmbroidaryRejectionAttribute()
    {
        $printRejection = $this->print_rejection ?? 0;
        $embroidaryRejection = $this->embroidary_rejection ?? 0;

        return ($printRejection > $embroidaryRejection) ? $printRejection : $embroidaryRejection;
    }

    public function printFactoryDeliveryQuantity()
    {
        return $this->quantity - ($this->total_rejection + $this->print_factory_receive_rejection + $this->print_factory_delivery_rejection);
    }


    public static function getFirstInputDateAndFloorOfOrder($order_id)
    {
        $bundleCardQuery = self::where(['order_id' => $order_id, 'status' => 1])
            ->whereNotNull('input_date')
            ->orderBy('input_date', 'asc')
            ->first();
        $data['input_date'] = '';
        $data['floor_no'] = '';
        if (isset($bundleCardQuery->cutting_inventory->cutting_inventory_challan)) {
            $cutting_inventory_challan = $bundleCardQuery
                ->cutting_inventory
                ->cutting_inventory_challan;
            $input_date = $cutting_inventory_challan->input_date;
            $data['input_date'] = date('d/m/Y', strtotime($input_date));
            $data['floor_no'] = $cutting_inventory_challan->floor->floor_no;
        }

        return $data;
    }

    public static function dateColorWiseBundleCount($cutting_date, $cutting_table_id, $purchase_order_id, $color_id)
    {
        return self::where([
            'cutting_table_id'  => $cutting_table_id,
            'purchase_order_id' => $purchase_order_id,
            'color_id'          => $color_id,
            'status'            => 1
        ])
            ->whereDate('cutting_date', $cutting_date)
            ->count();
    }

    public static function getLastCuttingNo($orderId, $lotIds)
    {
        $colorIds = Lot::whereIn('id', $lotIds)->get()->pluck('color_id')->all();

        $cuttingNos = [];
        foreach ($colorIds as $colorId) {
            $bundleCard = BundleCard::where('order_id', $orderId)
                ->where('color_id', $colorId)
                ->orderBy('id', 'DESC')
                ->first();

            if ($bundleCard) {
                $cuttingNos[] = [
                    'color_id'        => $colorId,
                    'color_name'      => Color::findOrFail($colorId)->name,
                    'last_cutting_no' => $bundleCard->cutting_no,
                    'cutting_no'      => (int)$bundleCard->cutting_no + 1
                ];
            } else {
                $cuttingNos[] = [
                    'color_id'        => $colorId,
                    'color_name'      => Color::findOrFail($colorId)->name,
                    'last_cutting_no' => 0,
                    'cutting_no'      => 1
                ];
            }
        }
        return $cuttingNos;
    }
}
