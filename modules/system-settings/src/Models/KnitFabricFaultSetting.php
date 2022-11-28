<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnitFabricFaultSetting extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $fillable = [
        'factory_id',
        'sequence',
        'name',
        'status',
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }
}
