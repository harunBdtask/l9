<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class OperationBulletin extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'operation_bulletins';

    protected $fillable = [
    	'floor_id',
    	'line_id',
        'buyer_id',
        'order_id',
        'proposed_target',
        'input_date',
        'prepared_date',
        'pattern_status',
        'input_date',
        'prepared_date',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function floor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Floor', 'floor_id')->withDefault();
    }

    public function line(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id')->withDefault();
    }

    public function operationBulletinDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OperationBulletinDetail::class, 'operation_bulletin_id');
    }

}
