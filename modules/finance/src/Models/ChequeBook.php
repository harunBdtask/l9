<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int bank_id
 * @property int bank_account_id
 * @property string cheque_book_no
 * @property string cheque_no_from
 * @property string cheque_no_to
 * @property string total_page
 * @property int created_by
 * @property int updated_by
 * @property int deleted_by
 * @property HasMany details
 */
class ChequeBook extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'cheque_books';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bank_id',
        'bank_account_id',
        'cheque_book_no',
        'cheque_no_from',
        'cheque_no_to',
        'total_page',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'bank_id' => 'integer',
        'bank_account_id' => 'integer',
        'cheque_book_no' => 'string',
        'cheque_no_from' => 'integer',
        'cheque_no_to' => 'integer',
        'total_page' => 'integer',
    ];

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $builder) use ($search) {
            $builder->where('cheque_book_no', 'LIKE', "%${search}%");
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhereHas('bankAccount',function ($query) use ($search) {
                $query->where('account_number', 'LIKE', "%${search}%");
            });
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhereHas('bank', function ($query) use ($search) {
                $query->whereHas('account', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%${search}%");
                });
            });
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(ChequeBookDetail::class, 'cheque_book_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withDefault();
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id')->withDefault();
    }
}
