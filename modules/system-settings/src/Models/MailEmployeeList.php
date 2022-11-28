<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailEmployeeList extends Model
{
    use SoftDeletes;

    protected $table = 'mail_employee_lists';

    protected $dates = ['deleted_at' ];
}
