<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'shifts';

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'extra_time',
        'factory_id',
    ];
    protected $dates = ['deleted_at'];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }
}
