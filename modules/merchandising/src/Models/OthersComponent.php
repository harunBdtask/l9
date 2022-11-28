<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;

class OthersComponent extends Model
{
    protected $table = 'others_components';
    protected $fillable = [
        'component', 'created_at', 'updated_at',
    ];
}
