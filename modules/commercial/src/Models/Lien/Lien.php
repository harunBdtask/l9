<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Lien;

use App\FactoryIdTrait;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\AdvisingBank;

class Lien extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FactoryIdTrait;
    use ModelCommonTrait;
    protected $table = "liens";

    protected $fillable = [
        'id',
        'lien_no',
        'bank_id',
        'lien_date',
        'factory_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->lien_no = getPrefix() . 'L-TD-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(AdvisingBank::class, 'bank_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(LienDetail::class,'lien_id');
    }

}
