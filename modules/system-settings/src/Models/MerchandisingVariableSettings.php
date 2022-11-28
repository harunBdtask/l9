<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Skeleton\Traits\CommonBooted;

class MerchandisingVariableSettings extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CommonBooted;

    protected $table = "merchandising_variable_settings";
    protected $primaryKey = "id";
    protected $fillable = ["factory_id", "variables_name", "variables_details", 'buyer_id'];
    protected $casts = [
        "variables_name" => Json::class,
        "variables_details" => Json::class,
    ];
}
