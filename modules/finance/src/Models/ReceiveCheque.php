<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiveCheque extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'fi_receive_cheques';
    protected $primaryKey = 'id';
    protected $fillable = [
        'voucher_no',
        'receive_bank_id',
        'cheque_no',
        'cheque_due_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function scopeSearch(Builder $builder, $search)
    {
        $builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('voucher_no', 'LIKE', "%{$search}%");
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhere('cheque_no', 'LIKE', "%{$search}%");
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhereDate('cheque_due_date', $search);
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhereHas('receiveBank', function ($query) use ($search) {
                $query->orWhere('name','LIKE', "%{$search}%");
            });
        });
    }

    public function receiveBank(): BelongsTo
    {
        return $this->belongsTo(ReceiveBank::class, 'id')->withDefault();
    }
}
