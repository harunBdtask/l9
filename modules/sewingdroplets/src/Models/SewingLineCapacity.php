<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class SewingLineCapacity extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'sewing_line_capacities';

    protected $fillable = [
        'floor_id',
        'line_id',
        'operator',
        'helper',
        'absent_percent',
        'working_hour',
        'working_minutes',
        'line_efficiency',
        'capacity_available_minutes',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public function floor()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Floor', 'floor_id');
    }

    public function line()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id');
    }

}
