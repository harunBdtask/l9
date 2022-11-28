<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSignatureDetail extends Model
{

    protected $table = 'report_signature_details';
    protected $fillable = [
        'report_signature_id',
        'designation',
        'name',
        'username',
        'user_id',
        'image',
        'signature_type',
        'sequence'
    ];
}
