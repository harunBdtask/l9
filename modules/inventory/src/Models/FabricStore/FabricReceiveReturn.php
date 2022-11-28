<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FabricReceiveReturn extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'fabric_receive_returns';

    const ABBR = 'FRR';

    protected $fillable = [
        'receive_return_no',
        'factory_id',
        'return_date',
        'mrr_no',
        'returned_to_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(FabricReceiveReturnDetail::class, 'receive_return_id');
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Stores::class, 'returned_to_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->receive_return_no = getPrefix() . static::ABBR . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function scopeSearch($query,$search)
    {
        return $query->when($search,function ($query) use($search){
            $query->where('receive_return_no','LIKE','%'.$search.'%')
            ->orWhere('return_date','LIKE','%'.$search.'%')
            ->orWhereHas('store', function ($query) use ($search){
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        });
    }
}
