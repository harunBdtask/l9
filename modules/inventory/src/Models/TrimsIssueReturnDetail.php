<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Casts\Json;
use App\Models\UIDModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrimsIssueReturnDetail extends UIDModel
{
    protected $table = 'trims_issue_return_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'trims_issue_return_id',
        'trims_issue_details_id',
        'uniq_id',
        'style_name',
        'item_id',
        'uom_id',
        'item_description',
        'item_color',
        'item_size',
        'floor',
        'room',
        'rack',
        'shelf',
        'bin',
        'buyer_order',
        'po_no',
        'shipment_date',
        'po_qty',
        'return_qty',
    ];

    protected $casts = [
        'po_no' => Json::class,
        'buyer_order' => Json::class,
    ];

    public static function getConfig(): array
    {
        return ['abbr' => "TIRD"];
    }

    public function issueReturn(): BelongsTo
    {
        return $this
            ->belongsTo(TrimsIssueReturn::class, 'trims_issue_return_id')
            ->withDefault();
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $this->rate * $this->receive_qty;
    }
}
