<?php


namespace SkylarkSoft\GoRMG\Inventory\Rules;


use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStockSummeryService;

abstract class StockQtyRule implements Rule
{

    public $message = 'Failed';
    public $idx;
    public $itemId;
    public $styleName /*Order Uniq No*/
    ;
    public $uomId;
    public $id;
    public $value;
    public $summary; /* Stock summary for item, with specific Style and UOM*/


    public function setValues($attribute, $value)
    {
        $idx = explode('.', $attribute)[0];

        if ( !is_numeric($idx) ) {
            $idx = 'details.' . explode('.', $attribute)[1];
        }

        $this->itemId = request($idx . '.item_id');
        $this->styleName = request($idx . '.style_name');
        $this->uomId = request($idx . '.uom_id');
        $this->id = request($idx . '.id');
        $this->value = $value;
        $this->idx = $idx;

        $this->summary = (new TrimsStockSummeryService)
            ->summary($this->styleName, $this->itemId, $this->uomId);
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

}