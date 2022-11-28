<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int cheque_book_id
 * @property string cheque_no
 * @property string paid_to
 * @property string amount
 * @property string cheque_date
 * @property string cheque_due_date
 * @property string status
 * @property int created_by
 * @property int updated_by
 * @property int deleted_by
 */
class ChequeBookDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const ACTIVE = 1, IN_ACTIVE = 2, PAID = 3, HOLD = 4, SCRIPT = 5;

    const STATUS_TYPE = [
        ['id' => 1, 'text' => 'Active'],
        ['id' => 2, 'text' => 'In-Active'],
        ['id' => 3, 'text' => 'Paid'],
        ['id' => 4, 'text' => 'Hold'],
        ['id' => 5, 'text' => 'Scrip'],
    ];

    protected $table = 'cheque_book_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cheque_book_id',
        'cheque_no',
        'paid_to',
        'amount',
        'cheque_date',
        'cheque_due_date',
        'status',
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
