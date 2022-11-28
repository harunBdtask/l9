<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\ASIConsumption;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class ASIConsumption extends Model
{
    use HasFactory;

    protected $table = 'asi_consumptions';

    protected $fillable = [
        'unique_id',
        'factory_id',
        'buyer_id',
        'season_id',
        'style_name',
        'created_date',
        'updated_date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->unique_id = getPrefix() . 'ASI-CE' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function details()
    {
        return $this->hasMany(AsiConsumptionDetails::class,'asi_consumption_id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class,'factory_id')->withDefault();
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class,'buyer_id')->withDefault();
    }

    public function season()
    {
        return $this->belongsTo(Season::class,'season_id')->withDefault();
    }


}
