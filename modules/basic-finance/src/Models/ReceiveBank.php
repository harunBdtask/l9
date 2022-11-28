<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiveBank extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'receive_banks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'short_name',
        'short_name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function scopeSearch(Builder $builder, $search)
    {
        $builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', "%{$search}%");
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhere('short_name', 'LIKE', "%{$search}%");
        });
    }

        public function receiveCheque(): HasMany
    {
        return $this->hasMany(ReceiveCheque::class, 'receive_bank_id', 'id');
    }
}
