<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\Casts\Json;
use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class B2BMarginLCDetail extends Model
{
    use SoftDeletes;
    use BelongsToFactory;

    protected $table = 'b_to_b_margin_lc_details';

    protected $fillable = [
        'b_to_b_margin_lc_id',
        'primary_master_contract_id',
        'factory_id',
        'export_lc_id',
        'sales_contract_id',
        'buyer_id',
        'lc_sc_no',
        'lc_sc_value',
        'current_distribution',
        'cumulative_distribution',
        'occupied_percentage',
        'status',
    ];
    protected $casts = [
        'buyer_id' => Json::class,
    ];

    protected $appends = [
        'buyer_names'
    ];
    public function getBuyerNamesAttribute()
    {
        $names = null;
        if ($this->buyer_id && is_array($this->buyer_id)){
            $names = Buyer::query()->whereIn('id', $this->buyer_id)->get(['id', 'name']);
        }else{
            $names = Buyer::query()->where('id', $this->buyer_id)->get(['id', 'name']);
        }
        return $names;
    }
    

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function b2bMargin(): BelongsTo
    {
        return $this->belongsTo(B2BMarginLC::class, 'b_to_b_margin_lc_id')->withDefault();
    }

    public function salesContract(): BelongsTo
    {
        return $this->belongsTo(SalesContract::class, 'lc_sc_no', 'contract_number')->withDefault();
    }

    public function exportLC(): BelongsTo
    {
        return $this->belongsTo(ExportLC::class, 'lc_sc_no', 'lc_number')->withDefault();
    }

    public function primaryMasterContract(): BelongsTo
    {
        return $this->belongsTo(PrimaryMasterContract::class, 'primary_master_contract_id')->withDefault();
    }
}
