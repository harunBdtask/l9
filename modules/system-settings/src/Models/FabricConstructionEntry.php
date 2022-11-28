<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricConstructionEntry extends Model
{
    use HasFactory;

    protected $fillable = ['construction_name'];
}
