<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\FactoryIdTrait;

class NextSchedule extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'next_schedule_date',
        'floor_id',
        'line_id',
        'buyer_id',
        'order_id',
        'output_finish_date',
        'created_by',
        'updated_by',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
    	return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function floor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
    	return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Floor', 'floor_id')->withDefault();
    }

    public function line(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
    	return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id')->withDefault();
    }

    public static function getNextSchedule($lineId)
    {
    	$data = self::where([
    		'line_id' => $lineId
    	])->orderBy('updated_at', 'desc')->first();

    	return $data->order->order_style_no ?? '';
    }

}
