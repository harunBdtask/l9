<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\GreyReceive;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;

class GreyReceiveDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'grey_receive_id',
        'factory_id',
        'knitting_program_id',
        'plan_info_id',
        'knitting_program_roll_id',
        'yarn_composition_id',
        'yarn_count_id',
        'factory_name',
        'book_company',
        'knitting_source_value',
        'buyer_name',
        'style_name',
        'unique_id',
        'po_no',
        'booking_no',
        'body_part',
        'color_type',
        'fabric_description',
        'item_color',
        'program_no',
        'production_qty',
        'pcs_production_qty',
        'scanable_barcode',
        'yarn_lot',
        'yarn_count_value',
        'yarn_composition_value',
        'yarn_brand',
        'delivery_status',
        'yarn_description',
    ];

    protected $casts = [
        'yarn_composition_id' => Json::class,
        'yarn_count_id' => Json::class,
        'yarn_count_value' => Json::class,
        'yarn_composition_value' => Json::class,
        'yarn_lot' => Json::class,
        'yarn_brand' => Json::class,

    ];

//    protected $appends = ['yarn_description'];

//    public function getYarnDescriptionAttribute()
//    {
//        $knitData = KnittingProgram::with('yarnRequisition.details')->where('id', $this->attributes['knitting_program_id'])->first();
//        return collect($knitData['yarnRequisition']['details'])->map(function ($item) {
//            $yarn_brand = $item['yarn_brand'] ?? null;
//            $yarn_lot = $item['yarn_lot'] ?? null;
//            $yarn_composition = $item['composition']['yarn_composition'] ?? null;
//            $yarn_count = $item['yarn_count']['yarn_count'] ?? null;
//            return $yarn_lot . ', ' . $yarn_count . ', ' . $yarn_brand . ', ' . $yarn_composition;
//        });
//    }

    public function bodyPartData(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part')->withDefault();
    }

    public function colorTypeData(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type')->withDefault();
    }
}
