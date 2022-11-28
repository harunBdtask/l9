<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrGroup extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = "hr_groups";
    protected $primaryKey = "id";
    protected $fillable = [
        "name",
        "medical_fee",
        "transport_fee",
        "food_fee"
    ];
}
