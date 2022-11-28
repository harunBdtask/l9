<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models\Procurements;

use App\Contracts\AuditAbleContract;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\DyesStore\Models\DsBrand;
use SkylarkSoft\GoRMG\DyesStore\Models\DsInvItemCategory;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DsUom;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsBrand;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvItemCategory;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsUom;
use SkylarkSoft\GoRMG\SystemSettings\Models\Brand;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class ProcurementRequisitionDetail extends Model implements AuditAbleContract
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use AuditAble;

    const ITEM_CATEGORY_RELATIONS = [
        'merchandising' => Item::class,
        'general_store' => GsInvItemCategory::class,
        'dyes_store' => DsInvItemCategory::class,
    ];

    const ITEM_RELATIONS = [
        'merchandising' => ItemGroup::class,
        'general_store' => GsItem::class,
        'dyes_store' => DsItem::class,
    ];

    const BRAND_RELATIONS = [
        'merchandising' => Brand::class,
        'general_store' => GsBrand::class,
        'dyes_store' => DsBrand::class,
    ];

    const UOM_RELATIONS = [
        'merchandising' => UnitOfMeasurement::class,
        'general_store' => GsUom::class,
        'dyes_store' => DsUom::class,
    ];

    protected $table = 'procurement_requisition_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'procurement_requisition_id',
        'date',
        'item_type',
        'item_category_id',
        'item_id',
        'item_description',
        'brand_id',
        'origin',
        'uom_id',
        'qty',
        'expected_delivery_date',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function procurementRequisition(): BelongsTo
    {
        return $this->belongsTo(ProcurementRequisition::class, 'procurement_requisition_id')
            ->withDefault();
    }

    public function itemCategory(): ?BelongsTo
    {
        if (isset($this->attributes['item_type'])) {
            $relation = self::ITEM_CATEGORY_RELATIONS[$this->attributes['item_type']];

            return $this->belongsTo($relation, 'item_category_id')->withDefault();
        }

        return null;
    }

    public function item(): ?BelongsTo
    {
        if (isset($this->attributes['item_type'])) {
            $relation = self::ITEM_RELATIONS[$this->attributes['item_type']];

            return $this->belongsTo($relation, 'item_id')->withDefault();
        }

        return null;
    }

    public function brand(): ?BelongsTo
    {
        if (isset($this->attributes['item_type'])) {
            $relation = self::BRAND_RELATIONS[$this->attributes['item_type']];

            return $this->belongsTo($relation, 'brand_id')->withDefault();
        }

        return null;
    }

    public function uom(): ?BelongsTo
    {
        if (isset($this->attributes['item_type'])) {
            $relation = self::UOM_RELATIONS[$this->attributes['item_type']];

            return $this->belongsTo($relation, 'uom_id')->withDefault();
        }

        return null;
    }

    public function moduleName(): string
    {
        return 'basic-finance';
    }

    public function path(): string
    {
        return "/basic-finance/procurement/requisitions/create?id=$this->procurement_requisition_id";
    }
}
