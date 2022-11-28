<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/24/19
 * Time: 3:27 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Skylarksoft\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderColorBreakdown;

class OrderColorBreakdownDelete
{
    private $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    public function handle()
    {
        $newColorIds = request()->color_ids;
        $existingColorIds = OrderColorBreakdown::where('order_id', $this->order_id)
            ->pluck('color_id')
            ->toArray();

        $deleted_color_ids = array_diff($existingColorIds, $newColorIds);
        if (count($deleted_color_ids)) {
            $isCreatedBundleCard = BundleCard::where('order_id', $this->order_id)
                ->whereIn('color_id', $deleted_color_ids)
                ->count();
            if ($isCreatedBundleCard) {
                return 501; // color can not update/delete after cutting
            }
        }

        $is_delete = OrderColorBreakdown::where('order_id', $this->order_id)->delete();

        return $is_delete;
    }
}
