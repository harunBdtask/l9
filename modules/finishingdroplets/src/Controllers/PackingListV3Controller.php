<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\PackingListV3Export;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\GarmentPackingProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Requests\GarmentPackingProductionRequest;
use SkylarkSoft\GoRMG\Finishingdroplets\Requests\PackingListSearchRequest;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3\PackingListFormatter;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PackingListV3Controller extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $packing = GarmentPackingProduction::query()
            ->with([
                'buyer', 'order',
                'purchaseOrder'
            ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('buyer', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('order', function ($q) use ($search) {
                    $q->where('style_name', 'LIKE', "%{$search}%");
                })->orWhereHas('purchaseOrder', function ($q) use ($search) {
                    $q->where('po_no', 'LIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate();

        return view('finishingdroplets::finishing-packing-list-v3.index', compact('packing'));
    }

    public function create()
    {
        return view('finishingdroplets::finishing-packing-list-v3.create');
    }

    /**
     * @param PackingListSearchRequest $request
     * @return mixed
     */
    public function search(PackingListSearchRequest $request)
    {
        $po = $request->get('po_id');
        $packingAssortment = $request->get('packing_assortment');

        $purchaseOrder = PurchaseOrder::query()
            ->with([
                'purchaseOrderDetails.color:id,name',
                'purchaseOrderDetails.size:id,name'
            ])->findOrFail($po);
        $packingAssortmentFormatter = PackingListFormatter::setAssortment($packingAssortment);
        return $packingAssortmentFormatter->setPo($purchaseOrder)->format();
    }

    /**
     * @param GarmentPackingProduction $garmentPackingProduction
     * @param GarmentPackingProductionRequest $request
     * @return JsonResponse
     */
    public function store(
        GarmentPackingProduction        $garmentPackingProduction,
        GarmentPackingProductionRequest $request
    ): JsonResponse
    {
        try {
            $garmentPackingProduction->fill($request->all())->save();
            return response()->json([
                'data' => $garmentPackingProduction,
                'message' => 'Successfully data stored',
                'code' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(GarmentPackingProduction $garmentPackingProduction)
    {
        return view('finishingdroplets::finishing-packing-list-v3.view.view',
            compact('garmentPackingProduction')
        );
    }

    public function excel(GarmentPackingProduction $garmentPackingProduction): BinaryFileResponse
    {
        return Excel::download(new PackingListV3Export($garmentPackingProduction), 'packing_list_v3.xlsx');
    }

    /**
     * @param GarmentPackingProduction $garmentPackingProduction
     * @return JsonResponse
     */
    public function edit(GarmentPackingProduction $garmentPackingProduction): JsonResponse
    {
        $garmentPackingProduction->load('purchaseOrder.country');
        return response()->json([
            'data' => $garmentPackingProduction,
            'code' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * @param GarmentPackingProduction $garmentPackingProduction
     * @param GarmentPackingProductionRequest $request
     * @return JsonResponse
     */
    public function update(
        GarmentPackingProduction        $garmentPackingProduction,
        GarmentPackingProductionRequest $request
    ): JsonResponse
    {
        try {
            $garmentPackingProduction->fill($request->all())->save();
            return response()->json([
                'message' => 'Successfully data updated',
                'code' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param GarmentPackingProduction $garmentPackingProduction
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(GarmentPackingProduction $garmentPackingProduction): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $garmentPackingProduction->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function getDefaultValues(): JsonResponse
    {
        $defaults['factoryId'] = factoryId();
        $defaults['assortment'] = GarmentPackingProduction::SOLID_COLOR_SOLID_SIZE;
        return response()->json($defaults);
    }
}
