<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/19/19
 * Time: 12:39 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Sample;

use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_composition;

class CompositionList
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $composition_value = $this->request->composition_value;
        $compositions = Fabric_composition::where('yarn_composition', 'LIKE', "$composition_value%");
        $html = '';
        if ($compositions->count() > 0) {
            $html .= '<ul class="append-suggetion">';
            foreach ($compositions->get() as $composition) {
                $html .= '<li class="composition-list" id="' . $composition->id . '">' . $composition->yarn_composition . '</li>';
            }
            $html .= '</ul>';
        }
        echo $html;
        exit;
    }
}
