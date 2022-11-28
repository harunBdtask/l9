<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialParameterSetup extends Model
{
    protected $table = 'financial_parameter_setups';
    protected $guarded = [];

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault(['factory_name' => 'N\A']);
    }
}
