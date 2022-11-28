<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemSubgroup extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'item_subgroups';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status', 'created_by', 'updated_by', 'deleted_by'];

    public function scopeSearch(Builder $builder, $search)
    {
        $builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', "%$search%");
        });
    }
}
