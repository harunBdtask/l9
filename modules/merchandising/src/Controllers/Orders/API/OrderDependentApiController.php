<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API;

use App\Facades\DecorateWithCacheFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use Symfony\Component\HttpFoundation\Response;

class OrderDependentApiController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function getFactories(): JsonResponse
    {
        $data = Factory::query()->userWiseFactories()->get();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getBuyers(Request $request): JsonResponse
    {
        $data = Buyer::query()
            //->where('factory_id', $request->get('factoryId'))
            ->where('status', 'Active')
            ->filterWithAssociateFactory('buyerWiseFactories', $request->get('factoryId'))
            ->permittedBuyer()
            ->get();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDealingMerchant(Request $request): JsonResponse
    {
        $data = Team::query()
            ->with(['teamWiseFactories', 'member:id,screen_name'])
            ->withoutGlobalScopes()
            ->filterWithAssociateFactory('teamWiseFactories', factoryId())
            ->get();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getJobs(Request $request): JsonResponse
    {
        $factory_id = $request->get("factoryId");
        $buyer_id = $request->get("buyerId") ?? null;
        $dealingMerchantId = $request->get("dealingMerchantId") ?? null;
        $jobs = Order::query()->where("factory_id", $factory_id)
            ->when($buyer_id, function ($query) use ($buyer_id) {
                return $query->where("buyer_id", $buyer_id);
            })
            ->when($dealingMerchantId, function ($query) use ($dealingMerchantId) {
                return $query->where('dealing_merchant_id', $dealingMerchantId);
            })
            ->pluck("job_no", "job_no");

        return response()->json($jobs, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getJobWisePo(Request $request): JsonResponse
    {
        $factory_id = $request->get("factoryId");
        $buyer_id = $request->get("buyerId") ?? null;
        $job_no = $request->get("jobNo");
        $dealingMerchantId = $request->get("dealingMerchantId") ?? null;

        $order = Order::query()
            ->when($buyer_id, function ($query) use ($buyer_id, $factory_id, $job_no) {
                return $query->where([
                    "factory_id" => $factory_id,
                    "buyer_id" => $buyer_id,
                    "job_no" => $job_no,
                ]);
            })
            ->when($dealingMerchantId, function ($query) use ($dealingMerchantId, $job_no, $factory_id) {
                return $query->where([
                    "factory_id" => $factory_id,
                    "dealing_merchant_id" => $dealingMerchantId,
                    "job_no" => $job_no,
                ]);
            })
            ->first();
        $po_no = PurchaseOrder::query()
            ->where("order_id", $order->id)
            ->pluck("po_no", "po_no")
            ->prepend("All Po", 'All');
        $response = [
            "style_name" => $order->style_name,
            "po" => $po_no,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSeasons(Request $request): JsonResponse
    {
        $factory_id = $request->get("factory_id");
        $buyer_id = $request->get("buyerId") ?? null;
        $seasons = Season::query()
            ->where('factory_id', $factory_id)
            ->where('buyer_id', $buyer_id)
            ->get();
        return response()->json($seasons, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategories(Request $request): JsonResponse
    {
        $data = ProductCateory::query()
            ->withoutGlobalScopes()
            ->filterWithAssociateFactory('productCategoryWiseFactories', $request->get('factoryId'))
            ->get();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDepartments(Request $request): JsonResponse
    {
        $data = ProductDepartments::all();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function loadSeasons(Request $request): JsonResponse
    {
        $data = Season::query()
            ->where('buyer_id', $request->get('buyerId'))
            ->get();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function loadTeamMembers(Request $request): JsonResponse
    {
        $data = Team::query()
            ->with('member')
//            ->where('role', 'Member')
            ->where('team_name', $request->get('teamName'))
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'memberId' => $team->member_id,
                    'team_name' => $team->team_name,
                    'name' => $team->member->first_name . ' ' . $team->member->last_name,
                ];
            });

        return response()->json($data);
    }
}
