<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use DB;
use SkylarkSoft\GoRMG\Settings\Models\Uom;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;

class DsInvItemCategory extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    protected $table = "ds_inv_items_category";
    protected $primaryKey = "id";
    protected $fillable = [
        "name",
        "code",
        "description",
        "parent_id",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%');
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, "parent_id")->withDefault();
    }

    public function items(): HasMany
    {
        return $this->hasMany(DsItem::class, 'category_id')->orderBy('name', 'ASC');
    }
}
