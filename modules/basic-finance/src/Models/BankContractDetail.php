<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankContractDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'bank_contract_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bank_id',
        'name',
        'designation',
        'contract_number',
        'email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withDefault();
    }

}
