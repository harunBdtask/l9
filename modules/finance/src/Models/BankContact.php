<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankContact extends Model
{
    use SoftDeletes;

    protected $table = 'fi_bank_contacts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bank_id',
        'name',
        'designation',
        'contact_number',
        'email'
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withDefault();
    }

}
