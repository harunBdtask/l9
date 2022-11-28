<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use App\Contracts\AuditAbleContract;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostingDetails extends Model implements AuditAbleContract
{
    use SoftDeletes;
    use AuditAble;

    protected $table = 'costing_details';

    protected $casts = [
        'details' => Json::class,
    ];

    const FABRIC_UOM = [
        1 => "Kg",
        2 => "Yards",
        3 => "Meter",
        4 => "Pcs",
    ];

    protected $fillable = ['price_quotation_id', 'type', 'details'];

    public function getDetailsAttribute()
    {
        return json_decode($this->attributes['details'], true);
    }

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return url("budgeting/$this->id/costing");
    }
}
