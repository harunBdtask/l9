<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;


class HeadingLocalization extends Model
{
    protected $table = 'heading_localizations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'localization',
    ];
    protected $casts = [
        'localization' => Json::class
    ];

}
