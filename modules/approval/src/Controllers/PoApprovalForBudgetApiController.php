<?php


namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Approval\Filters\Filter;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class PoApprovalForBudgetApiController extends Controller
{
    private $buyerId;

    public function __invoke(Request $request)
    {
        try {
            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');
            $this->buyerId = $buyerId;
            $styleName = $request->get('style_name');
            $poNo = $request->get('po_no');
            $uniqueId = $request->get('unique_id');
            $approvalType = $request->get('approval_type');
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');

            $orders = Order::query()
                ->with(['purchaseOrders' => function ($query) use ($poNo, $approvalType) {
                    $query->when($poNo, Filter::applyFilter('po_no', $poNo))
                        ->when($approvalType, function ($query) use ($approvalType) {
                            $query->when($approvalType == 1, function ($query) use ($approvalType) {
                                $query->where('ready_to_approved', '=', 1)
                                    ->where('is_approved', '=', 0);
                            })->when($approvalType == 2, function ($query) {
                                $query->where('is_approved', 1);
                            });
                        });
                }, 'buyer:id,name', 'dealingMerchant:id,screen_name'])
                ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
//                ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
                ->whereIn('buyer_id', $this->getBuyerList())
                ->when($styleName, Filter::applyFilter('style_name', $styleName))
                ->when($uniqueId, Filter::applyFilter('job_no', $uniqueId))
                ->get()->map->approveForBudgetFilterFormat()->filter(function ($collection) {
                    return count($collection['details']);
                })->values();

            return response()->json($orders, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPriority()
    {
        $buyers = $this->buyerId;

        return Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'user_id' => userId(),
                'page_name' => 'PO Approval'])
            ->when($buyers, function ($query) use ($buyers) {
                return $query->whereRaw('FIND_IN_SET(?,buyer_ids)', [$buyers]);
            })
            ->get();
    }

    public function getBuyerList()
    {
        $priority = $this->getLastPriority() ?? null;
        return $priority ? explode(',', $priority['buyer_ids']) : [];
    }

    public function getLastPriority()
    {
        return $this->getPriority()->last();
    }

}
