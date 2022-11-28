<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Illuminate\Database\Eloquent\Model;

class DsDepartment extends Model
{
    protected $table = "ds_departments";
    protected $primaryKey = "id";
    protected $fillable = ['name'];
}
