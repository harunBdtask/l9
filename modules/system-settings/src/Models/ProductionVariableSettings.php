<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Skeleton\Traits\CommonBooted;

class ProductionVariableSettings extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $primaryKey = "id";
    protected $table = "production_variable_settings";
    protected $fillable = ["factory_id", "variables_name", "variables_details", "created_by", "updated_by", "deleted_by"];
}
