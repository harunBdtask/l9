<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;

class DsRack extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    protected $table = "ds_racks";
    protected $primaryKey = "id";
    protected $fillable = ["name", "description", "created_by", "updated_by", "deleted_by"];

    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        });
    }
}
