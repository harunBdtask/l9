<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuttingTable extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
         'factory_id',
         'cutting_floor_id',
        'table_no',
    ];

    protected $dates = ['deleted_at'];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
    
    public function cuttingFloor()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id');
    }

    public function bundleCards()
    {
        return $this->hasMany(BundleCard::class, 'cutting_table_id')->where('status', 1);
    }

    public function cutting_target()
    {
        return $this->hasOne('Skylarksoft\Iedroplets\Models\CuttingTarget', 'cutting_table_id', 'id');
    }

    public function todaysCutting()
    {
        return $this->hasMany(BundleCard::class, 'cutting_table_id')
            ->where('status', 1)
            ->whereDate('updated_at', date('Y-m-d'));
    }

    public static function getCuttingTables($cuttingFloorId)
    {
        return self::where('cutting_floor_id', $cuttingFloorId)
            ->pluck('table_no', 'id')
            ->all();
    }
}
