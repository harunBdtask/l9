<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use SoftDeletes;

    protected $table = 'vouchers';

    protected $fillable = [
        'voucher_no',
        'type_id',
        'trn_date',
        'file_no',
        'reference_no',
        'project_id',
        'unit_id',
        'currency_id',
        'paymode',
        'credit_account',
        'debit_account',
        'to',
        'from',
        'bank_id',
        'receive_bank_id',
        'cheque_no',
        'receive_cheque_no',
        'cheque_date',
        'cheque_due_date',
        'amount',
        'details',
        'status_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
        'group_company',
        'ac_number'
    ];

    protected $dates = [
        'trn_date'
    ];

    const VOUCHER_TYPE = [
        1 => 'debit',
        2 => 'credit',
        3 => 'journal',
        4 => 'contra',
    ];

    public static $statuses = [
        0 => 'Created',
        1 => 'Checked',
        2 => 'Authorized',
        3 => 'Posted',
        4 => 'Amend',
        5 => 'Canceled',
    ];

    public static $types = [
        1 => 'Debit Voucher',
        2 => 'Credit Voucher',
        3 => 'Journal Voucher',
        4 => 'Contra Voucher',
    ];

    public static function generateVoucherNo($type)
    {
        $id = Voucher::query()->max('id') + 1;
        $code = str_pad($id, 8, 0, STR_PAD_LEFT);

        switch ($type) {
            case 'debit':
                return 'DV-' . $code;
            case 'credit':
                return 'CV-' . $code;
            case 'journal':
                return 'JV-' . $code;
            case 'contra':
                return 'COV-' . $code;
        }
    }

//    public static function generateVoucherNo($type)
//    {
//        switch ($type) {
//            case 'debit':
//                $initial = 'DV-';
//                break;
//            case 'credit':
//                $initial = 'CV-';
//                break;
//            case 'journal':
//                $initial = 'JV-';
//                break;
//            case 'contra':
//                $initial = 'COV-';
//                break;
//        }
//
//        $prevId = Voucher::query()->where('voucher_no', 'like', $initial . '%')->max('voucher_no') ?? 0;
//        $code = str_pad((str_replace($initial,'',$prevId) + 1), 8, 0, STR_PAD_LEFT);
//
//        return $initial.$code;
//    }

    const CREATED = 0, CHECKED = 1, AUTHORIZED = 2, POSTED = 3, AMEND = 4, CANCELED = 5;

    const DEBIT_VOUCHER = 1;
    const CREDIT_VOUCHER = 2;
    const JOURNAL_VOUCHER = 3;
    const CONTRA_VOUCHER = 4;

    const BANK = 1;
    const CASH = 2;

    public function getTypeAttribute(): string
    {
        return self::$types[$this->attributes['type_id']];
    }

    public function getStatusAttribute(): string
    {
        return self::$statuses[$this->attributes['status_id']];
    }

    public function getDetailsAttribute()
    {
        return json_decode($this->attributes['details']);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPreparedByAttribute()
    {
        return $this->createdBy;
    }

    public function getCheckedByAttribute()
    {
        $comment = $this->comments()->where('status_id', self::CHECKED)
            ->orderBy('id', 'DESC')
            ->first();
        if ($comment) {
            return $comment->commenter;
        }

        return null;
    }

    public function getAuthorizedByAttribute()
    {
        $comment = $this->comments()->where('status_id', self::AUTHORIZED)
            ->orderBy('id', 'DESC')
            ->first();

        if ($comment) {
            return $comment->commenter;
        }

        return null;
    }

    public function comments(): HasMany
    {
        return $this->hasMany(VoucherComment::class, 'voucher_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(AcCompany::class, 'factory_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function cheque(): BelongsTo
    {
        return $this->belongsTo(ChequeBookDetail::class, 'cheque_no')->withDefault();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withDefault();
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withDefault();
    }

    public function receiveBank(): BelongsTo
    {
        return $this->belongsTo(ReceiveBank::class, 'receive_bank_id')->withDefault();
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public static function booted()
    {
        static::creating(function ($model) {
            if (in_array('created_by', $model->getFillable())) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (in_array('updated_by', $model->getFillable())) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => Auth::id(),
                    ]);
            }
        });
    }
}
