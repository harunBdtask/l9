<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class ArchivedPrintInventory extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'archived_print_inventories';

    protected $fillable = [
        'challan_no',
        'bundle_card_id',
        'factory_id',
        'status',
        'print_status',
        'type',
        'created_by'
    ];

    protected $dates = ['deleted_at'];

    public function bundle_card()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard', 'bundle_card_id');
    }

    public function archived_bundle_card()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\ArchivedBundleCard', 'bundle_card_id');
    }

    public function cuttingInventory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory', 'bundle_card_id', 'bundle_card_id');
    }

    public function archivedCuttingInventory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Inputdroplets\Models\ArchivedCuttingInventory', 'bundle_card_id', 'bundle_card_id');
    }

    public function printInventoryChallan()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Printembrdroplets\Models\printInventoryChallan', 'challan_no', 'challan_no');
    }

    public function archivedPrintInventoryChallanOperation()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Printembrdroplets\Models\ArchivedPrintInventoryChallan', 'challan_no', 'challan_no');
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id', 'id');
    }
}
