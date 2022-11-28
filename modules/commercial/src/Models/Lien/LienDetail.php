<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Lien;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Illuminate\Database\Eloquent\Model;

class LienDetail extends Model
{
    protected $table = 'lien_details';
    protected $fillable = [
        'id',
        'lien_id',
        'buyer_id',
        'buyer_name',
        'internal_file_no',
        'sales_contract_no',
        'sales_contract_id',
        'sales_contract_date',
        'sales_contract_value',
    ];

    public function lien(): BelongsTo
    {
        return $this->belongsTo(Lien::class, 'lien_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function sale_contact(): BelongsTo
    {
        return $this->belongsTo(SalesContract::class, 'sales_contact_id')->withDefault();
    }
}
