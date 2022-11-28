<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactoryTable;

class PrintReceiveInventoryChallan extends Model
{
    use SoftDeletes, FactoryIdTrait, CascadeSoftDeletes;

    const CHALLAN = 0;
    const TAG = 1;

    protected $table = 'print_receive_inventory_challans';

    protected $cascadeDeletes = ['print_receive_inventories'];

    protected $fillable = [
        'type',
        'challan_no',
        'operation_name',
        'table_id',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function print_receive_inventories()
    {
        return $this->hasMany(PrintReceiveInventory::class, 'challan_no', 'challan_no');
    }

    public function inventories()
    {
        return $this->print_receive_inventories();
    }

    public function print_table()
    {
       return $this->belongsTo(PrintFactoryTable::class, 'table_id')->withDefault();
    }

    public function createdBy()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\User', 'created_by')->withDefault();
    }
}