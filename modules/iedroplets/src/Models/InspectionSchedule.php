<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class InspectionSchedule extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'inspection_date',
        'inspection_quantity',
        'remarks',
        'status',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }
}
