<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use SoftDeletes;
    protected $table = 'seasons';
    protected $guarded = [];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
}
