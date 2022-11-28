<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;

class DsStoreModel extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    protected $table = "ds_stores";
    protected $primaryKey = "id";
    protected $fillable = ["name", "code", "sym", "description", "created_by", "updated_by", "deleted_by"];
}
