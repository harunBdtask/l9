<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrderDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\ItemFormatter;

class EmbellishmentColorSizeController extends Controller
{
    public function __invoke(Request $request, ItemFormatter $formatter)
    {
        $filters = [
            //'sensitivity' => $request->get('sensitivity'),
            'budget_unique_id' => $request->get('budget_unique_id'),
            'embellishment_work_order_id' => $request->get('embellishment_work_order_id'),
            'embellishment_id' => $request->get('item_id'),
        ];
        $item = EmbellishmentWorkOrderDetails::where($filters)
            ->firstOrFail();

        if ($this->wantsStoredDetails($item)) {
            return response()->json($item->details);
        }

        $item['breakdown'] = $item['breakdown']['details'];
        $breakdowns = $formatter->format($item, 'embellishment_booking');

        return response()->json($breakdowns);
    }

    private function wantsStoredDetails($item): bool
    {
        return $item->sensitivity == request('sensitivity') && $item->details;
    }
}
