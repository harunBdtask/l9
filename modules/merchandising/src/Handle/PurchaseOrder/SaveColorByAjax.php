<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 3:06 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class SaveColorByAjax
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function save()
    {
        $color = new Color();
        $color->name = $this->request->color_name;
        $color->save();

        return response()->json([
            'success' => 1,
            'color_id' => $color->id,
            'color_name' => $color->name,
        ]);
    }
}
