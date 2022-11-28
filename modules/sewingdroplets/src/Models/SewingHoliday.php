<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SewingHoliday extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = "sewing_holidays";

    protected $fillable = [
        'holiday',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id', 'id')->withDefault();
    }
}