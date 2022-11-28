<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 10/9/19
 * Time: 5:29 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Subcontract;

use Illuminate\Support\Facades\View;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderColorBreakdown;

class SubcontractFormGeneration
{
    private $buyer_id;
    private $booking_no;

    public function __construct($request)
    {
        $this->buyer_id = $request->buyer_id;
        $this->booking_no = $request->booking_no;
    }

    public function generate()
    {
        $data['order_colors'] = OrderColorBreakdown::with('color')->where('order_id', $this->booking_no)->get();
        $view = View::make('merchandising::subcontract.with_fabric_partial', $data);
        echo "$view";
        exit;
    }
}
