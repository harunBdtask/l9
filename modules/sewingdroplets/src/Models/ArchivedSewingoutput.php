<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class ArchivedSewingoutput extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'bundle_card_id',
        'output_challan_no',
        'challan_no',
        'line_id',
        'hour',
        'status',
        'factory_id',
        'user_id',
        'purchase_order_id',
        'color_id',
        'details',
    ];

    protected $dates = ['deleted_at'];

    public function bundlecard()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard', 'bundle_card_id');
    }

    public function archived_bundlecard()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\ArchivedBundleCard', 'bundle_card_id');
    }

    public function line()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id');
    }

    public function cuttingInventoryChallan()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan', 'challan_no', 'challan_no');
    }

    public function archivedCuttingInventoryChallan()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Inputdroplets\Models\ArchivedCuttingInventoryChallan', 'challan_no', 'challan_no');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id', 'id');
    }
    
    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color');
    }

    public function washing()
    {
        return $this->hasOne('SkylarkSoft\GoRMG\Washingdroplets\Models\Washing', 'bundle_card_id', 'bundle_card_id');
    }

    public function user()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\User', 'user_id')->withDefault();
    }
    
    public static function getDateWiseWorkingHourForPO($date, $purchase_order_id, $floor_id)
    {
        $query = self::selectRaw("MIN(sewingoutputs.created_at) as start_time, sewingoutputs.line_id, lines.floor_id")
            ->leftJoin('lines', 'lines.id', 'sewingoutputs.line_id')
            ->whereDate('sewingoutputs.created_at', $date)
            ->where('sewingoutputs.purchase_order_id', $purchase_order_id)
            ->where('lines.floor_id', $floor_id)
            ->groupBy('sewingoutputs.line_id')
            ->get();
        return $query;
    }

    public function getDetailsAttribute($value)
    {
        return json_decode($value,true);
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }
}
