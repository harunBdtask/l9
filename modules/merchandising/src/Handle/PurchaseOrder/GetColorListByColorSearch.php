<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 3:01 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class GetColorListByColorSearch
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $color_name = $this->request->color_name;
        $colors = Color::where('name', 'LIKE', "%$color_name%");
        $html = '';
        if ($colors->count() > 0) {
            $html .= '<ul class="append-suggetion">';
            foreach ($colors->get() as $color) {
                $html .= '<li class="color-list" id="' . $color->id . '">' . $color->name . '</li>';
            }
            $html .= '</ul>';
        }
    }
}
