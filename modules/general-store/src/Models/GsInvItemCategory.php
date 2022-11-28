<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use DB;
use SkylarkSoft\GoRMG\GeneralStore\Traits\CommonBooted;

class GsInvItemCategory extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    protected $table = "gs_inv_items_category";
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

    public function parent()
    {
        return $this->belongsTo(__CLASS__, "parent_id")->withDefault();
    }

    public function items()
    {
        return $this->hasMany(GsItem::class, 'category_id')->orderBy('name', 'ASC');
    }
}
