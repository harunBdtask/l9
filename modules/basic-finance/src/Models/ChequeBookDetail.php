<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChequeBookDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const ACTIVE = 1, IN_ACTIVE = 2, PAID = 3, HOLD = 4, SCRIPT = 5, CLEAR = 6, UNCLEAR = 7;

    const STATUS_TYPE = [
        ['id' => 1, 'text' => 'Active'],
        ['id' => 2, 'text' => 'In-Active'],
        ['id' => 3, 'text' => 'Paid'],
        ['id' => 4, 'text' => 'Hold'],
        ['id' => 5, 'text' => 'Script'],
        ['id' => 6, 'text' => 'Clear'],
        ['id' => 7, 'text' => 'Unclear'],
    ];

    protected $table = 'bf_cheque_book_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cheque_book_id',
        'cheque_no',
        'paid_to',
        'amount',
        'cheque_date',
        'cheque_due_date',
        'status',
        'clearing_date',
        'cleared_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'cheque_book_id' => 'integer',
        'cheque_no' => 'string',
        'paid_to' => 'string',
        'amount' => 'decimal:4',
        'cheque_date' => 'date:Y-m-d',
        'cheque_due_date' => 'date:Y-m-d',
        'status' => 'integer',
    ];

    public function chequeBook(): BelongsTo
    {
        return $this->belongsTo(ChequeBook::class)->withDefault();
    }

    public function voucher(): HasOne
    {
        return $this->hasOne(Voucher::class, 'cheque_no');
    }
}
