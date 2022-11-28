<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class CostingTemplate extends Model
{
    protected $table = 'costing_templates';

    protected $casts = [
        'details' => Json::class,
    ];

    protected $fillable = ["factory_id", "type", "buyer_id", "template_name", "details"];
}
