<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartyType extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = "party_types";

    protected $fillable = [
        'party_type',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }
}
