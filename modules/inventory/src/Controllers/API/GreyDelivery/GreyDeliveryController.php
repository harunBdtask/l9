<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\GreyDelivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Inventory\Models\GreyDelivery\GreyDelivery;
use SkylarkSoft\GoRMG\Inventory\Models\GreyDelivery\GreyDeliveryDetail;
use SkylarkSoft\GoRMG\Inventory\Models\GreyReceive\GreyReceiveDetails;
use SkylarkSoft\GoRMG\Inventory\Services\GreyDelivery\GreyDeliveryFormatterService;
use Throwable;

class GreyDeliveryController extends Controller
{
    public function index()
    {
        $greyDeliveries = GreyDelivery::query()->orderBy('id', 'desc')->paginate();
        return view('inventory::grey-delivery.index', compact('greyDeliveries'));
    }

    public function create()
    {
        return view('inventory::grey-delivery.create_update');
    }

    public function show(GreyDelivery $greyDelivery): JsonResponse
    {
        $greyDelivery = $greyDelivery->load(['details.receiveDetail.bodyPartData', 'details.receiveDetail.colorTypeData']);
        $greyDelivery['receive_detail'] = collect($greyDelivery->details)->map(function ($item) {
            return GreyDeliveryFormatterService::formatDetails($item);
        });
        $details = collect($greyDelivery)->except('details');
        return response()->json($details);
    }


    public function getDetails(Request $request): JsonResponse
    {
        $details = GreyReceiveDetails::query()
            ->where('factory_id', $request->get('factoryId'))
            ->where('delivery_status', 0)
            ->when($request->get('buyer_name') || $request->get('style_name'), function ($query) use ($request) {
                $query->Where('buyer_name', 'LIKE', $request->get('buyer_name'))
                    ->whereRaw('FIND_IN_SET("' . $request->get('style_name') . '",style_name)');
            })
            ->when($request->get('scanable_barcode'), function ($query) use ($request) {
                $query->where('scanable_barcode', $request->get('scanable_barcode'));
            })
            ->get();

        return response()->json($details);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $greyDelivery = new GreyDelivery();
            $greyDelivery->fill(collect($request)->all())->save();
            return response()->json($greyDelivery);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function storeDetails(GreyDelivery $greyDelivery, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            foreach ($request->all() as $item) {
                if (isset($item['id'])) {
                    $greyDelivery->details()->find($item['id'])->update(collect($item)->all());
                } else {
                    $greyDelivery->details()->create(collect($item)->all());
                }
            }

            $grey_receive_details_id = collect($request->all())->pluck('grey_receive_details_id')->unique()->values();
            GreyReceiveDetails::query()->whereIn('id', $grey_receive_details_id)->update(['delivery_status' => 1]);

            DB::commit();
            return response()->json($greyDelivery->load('details'));

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(GreyDelivery $greyDelivery)
    {
        try {
            DB::beginTransaction();
            $grey_receive_details_id = $greyDelivery->details()->pluck('grey_receive_details_id')->unique()->values();
            GreyReceiveDetails::query()->whereIn('id', $grey_receive_details_id)->update(['delivery_status' => 0]);
            $greyDelivery->delete();
            $greyDelivery->details()->delete();

            DB::commit();
            Session::flash('error', 'Data Deleted Successfully');
            return redirect('/inventory/grey-delivery');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error', 'Something went wrong !');
            return redirect('/inventory/grey-delivery');
        }
    }


    /**
     * @throws Throwable
     */
    public function destroyDetail(GreyDeliveryDetail $greyDeliveryDetail): JsonResponse
    {
        try {
            DB::beginTransaction();
            $grey_receive_details_id = $greyDeliveryDetail->grey_receive_details_id;
            GreyReceiveDetails::query()->where('id', $grey_receive_details_id)->update(['delivery_status' => 0]);
            $greyDeliveryDetail->delete();

            DB::commit();
            return response()->json('success');
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

}
