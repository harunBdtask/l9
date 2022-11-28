<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class VoucherComment extends Model
{
    protected $table = 'bf_voucher_comments';

    protected $fillable = [
        'voucher_id',
        'status_id',
        'comment',
        'commented_by',
    ];

    public function commenter()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
