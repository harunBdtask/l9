<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use DB;

class PrintReceiveInventory extends Model
{
    use FactoryIdTrait ,SoftDeletes;

    protected $table = 'print_receive_inventories';

    protected $fillable = [
        'challan_no',
        'bundle_card_id',
        'factory_id',
        'status',
        'production_challan_no',
        'production_status',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $bundle = BundleCard::find($model->bundle_card_id);
            $bundle->print_factory_receive_rejection = 0;
            $bundle->save();
        });

       /* static::updated(function ($printInventoryChallan) {
            try {
                $bundleCard = $printInventoryChallan->bundle_card;
                $bundle_card_id = $printInventoryChallan->bundle_card_id;
                $bundleQty = $bundleCard->quantity -
                    ($bundleCard->total_rejection + $bundleCard->print_factory_receive_rejection + $bundleCard->production_rejection_qty);
                DB::table('print_factory_reports')->where('bundle_card_id', $bundle_card_id)
                    ->update(['production_qty' => $bundleQty]);
            } catch (Exception $e) {
                \Log::info('MUHID: ' . $e->getMessage());
            }
        });*/
    }

    public static function getChallanNo()
    {
        $challan = (new static())->where([
            'status'     => 0,
            'created_by' => userId()
        ])->first();

        return $challan->challan_no ?? userId() . time();
    }

    public function bundle_card()
    {
        return $this->belongsTo(BundleCard::class, 'bundle_card_id');
    }

    public function print_receive_inventory_challan()
    {
        return $this->belongsTo(PrintReceiveInventoryChallan::class, 'challan_no', 'challan_no');
    }
}