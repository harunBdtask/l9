<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;

class SalesTargetDetails extends Model
{
    use SoftDeletes;
    protected $table = 'sales_target_details';
    protected $guarded = [];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
