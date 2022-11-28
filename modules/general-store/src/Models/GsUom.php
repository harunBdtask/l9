<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\GeneralStore\Traits\CommonBooted;

class GsUom extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    protected $table = "gs_uom";
    protected $primaryKey = "id";
    protected $fillable = ["name", "created_by", "updated_by", "deleted_by"];

    // protected $perPage = 2;

    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }
}
