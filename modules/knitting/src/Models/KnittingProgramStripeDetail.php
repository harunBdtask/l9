<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;

class KnittingProgramStripeDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'knitting_program_stripe_details';

    protected $fillable = [
        'knitting_program_id',
        'fabric_description',
        'fabric_nature_id',
        'fabric_nature',
        'item_color_id',
        'stripe_details',
        'body_part',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'stripe_details' => Json::class
    ];

    public function knittingProgram(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id', 'id')->withDefault();
    }

    public function itemColor()
    {
        return $this->belongsTo(Color::class, 'item_color_id', 'id')->withDefault();
    }
}
