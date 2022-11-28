<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;

class PrintEmbroideryQcInventory extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'print_embroidery_qc_inventories';

    protected $fillable = [
        'challan_no',
        'bundle_card_id',
        'status',
        'created_by',
        'deleted_by',
        'factory_id',        
    ];



   /* protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $bundle = BundleCard::find($model->bundle_card_id);
            $bundle->print_factory_receive_rejection = 0;
            $bundle->save();
        });
    }*/

    /*public static function getChallanNo()
    {
        $challan = (new static())->where([
            'status'     => 0,
            'created_by' => userId()
        ])->first();

        return $challan->challan_no ?? userId() . time();
    }*/

    public function bundle_card()
    {
        return $this->belongsTo(BundleCard::class)->withDefault();
    }

    public function print_received_invetory()
    {
        return $this->belongsTo(PrintReceiveInventory::class, 'bundle_card_id', 'bundle_card_id')->withDefault();
    }

    public function print_embroidery_qc_inventory_challan()
    {
        return $this->belongsTo(PrintEmbroideryQcInventoryChallan::class, 'challan_no', 'challan_no')->withDefault();
    }

    public function print_embroidery_delivery_challans()
    {
        return $this->hasMany(self::class, 'output_challan_no', 'challan_no');
    }
}
