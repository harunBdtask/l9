<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnittingFloor extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'knitting_floors';

    protected $fillable = [
        'name',
        'sequence',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }
}
