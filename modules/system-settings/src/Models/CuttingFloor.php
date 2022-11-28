<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuttingFloor extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'factory_id',
        'floor_no',
    ];

    protected $dates = ['deleted_at'];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

    public function cuttingTables()
    {
        return $this->hasMany(CuttingTable::class, 'cutting_floor_id');
    }

    public function bundleCards()
    {
        return $this->hasMany(BundleCard::class, 'floor_id') ->where('status', 1);
    }

    public function todaysCutting()
    {
        return $this->hasMany(BundleCard::class, 'floor_id')
            ->whereDate('updated_at', date('Y-m-d'))
            ->where('status', 1);
    }
}
