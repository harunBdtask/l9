<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 3:25 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Rules\UniquePoNoSameOrder;

class PurchaseOrderValidationRules
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function rules()
    {
        $rules = [
            'buyer_id' => 'required',
            'order_id' => 'required',
            'po_no' => ['required', new UniquePoNoSameOrder],
            'shipping_mode' => 'required',
            'packing_mode' => 'required',
            'po_quantity' => 'required',
            'ex_factory_date' => 'required',
            'order_uom' => 'required',
            'incoterm_id' => 'required',
            'incoterm_place_id' => 'required',
            'print' => 'required',
            'embroidery' => 'required',
        ];
        if ($this->request->order_uom == 2) {
            $rules['ratio.*'] = 'required';
        }

        return $rules;
    }
}
