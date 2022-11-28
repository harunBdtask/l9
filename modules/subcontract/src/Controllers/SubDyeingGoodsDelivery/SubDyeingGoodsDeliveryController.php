<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingGoodsDelivery;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingGoodsDelivery;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingGoodsDeliveryDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingGoodsDeliveryRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubGoodsDeliveryNotifyService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingGoodsDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $subDyeingGoodsDeliveries = SubDyeingGoodsDelivery::query()
            ->with([
                'supplier',
                'subDyeingUnit',
                'shift',
                'subDyeingGoodsDeliveryDetails',
            ])
            ->search($request)
            ->orderBy('id', 'desc')
            ->paginate();

        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select', 0);
        $dyeingUnits = SubDyeingUnit::query()->pluck('name', 'id')->prepend('Select', 0);
        $shifts = Shift::query()->pluck('shift_name', 'id')->prepend('Select', 0);
        $suppliers = Buyer::query()->pluck('name', 'id')->prepend('Select', 0);
        $goodsDeliveryUID = SubDyeingGoodsDelivery::query()->pluck('goods_delivery_uid', 'goods_delivery_uid')
            ->prepend('Select', 0);

        return view('subcontract::textile_module.sub-dyeing-goods-delivery.index', [
            'subDyeingGoodsDeliveries' => $subDyeingGoodsDeliveries,
            'factories' => $factories,
            'dyeingUnits' => $dyeingUnits,
            'shifts' => $shifts,
            'suppliers' => $suppliers,
            'goodsDeliveryUID' => $goodsDeliveryUID,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.sub-dyeing-goods-delivery.form');
    }

    /**
     * @param SubDyeingGoodsDeliveryRequest $request
     * @param SubDyeingGoodsDelivery $subDyeingGoodsDelivery
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        SubDyeingGoodsDeliveryRequest $request,
        SubDyeingGoodsDelivery        $subDyeingGoodsDelivery
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $subDyeingGoodsDelivery->fill($request->all())->save();

            $subDyeingGoodsDelivery->subDyeingGoodsDeliveryDetails()->createMany($request->get('details'));
            DB::commit();

            $this->notify($subDyeingGoodsDelivery, 'created');

            return response()->json([
                'data' => $subDyeingGoodsDelivery,
                'message' => 'Data stored successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingGoodsDelivery $subDyeingGoodsDelivery
     * @return JsonResponse
     */
    public function edit(SubDyeingGoodsDelivery $subDyeingGoodsDelivery): JsonResponse
    {
        try {
            $subDyeingGoodsDelivery->load([
                'supplier',
                'subDyeingUnit',
                'subDyeingGoodsDeliveryDetails.subTextileOrderDetail',
                'subDyeingGoodsDeliveryDetails.subDyeingBatchDetail',
                'subDyeingGoodsDeliveryDetails.subDyeingBatch.fabricColor',
                'shift',
            ]);

            $subDyeingGoodsDelivery->factory_name = $subDyeingGoodsDelivery->factory->factory_name;
            $subDyeingGoodsDelivery->supplier_name = $subDyeingGoodsDelivery->supplier->name;

            $subDyeingGoodsDelivery['details'] = $subDyeingGoodsDelivery
                ->getRelation('subDyeingGoodsDeliveryDetails')->map(function ($detail) use ($subDyeingGoodsDelivery) {
                    $prevQty = SubDyeingGoodsDeliveryDetail::query()
                        ->selectRaw('SUM(total_roll) AS totalRoll,SUM(delivery_qty) AS totalDeliveryQty')
                        ->when($detail->order_details_id, Filter::applyFilter('order_details_id', $detail->order_details_id))
                        ->when($detail->batch_details_id, Filter::applyFilter('batch_details_id', $detail->batch_details_id))
                        ->where('id', '!=', $detail->id)
                        ->first();

                    if ($subDyeingGoodsDelivery->entry_basis === 1) {
                        $detail['gsm'] = $detail->subDyeingBatchDetail->gsm;
                        $detail['dia_type_value'] = $detail->subDyeingBatchDetail->dia_type_value['name'] ?? null;
                        $detail['color_name'] = $detail->subDyeingBatch->fabricColor['name'];
                    } else {
                        $detail['gsm'] = $detail->gsm;
                        $detail['dia_type_value'] = $detail->dia_type_value['name'] ?? null;
                        $detail['color_name'] = $detail->color['name'];
                    }
                    $detail['prev_total_roll'] = $prevQty->totalRoll ?? null;
                    $detail['prev_delivery_qty'] = $prevQty->totalDeliveryQty ?? null;
                    $detail['order_qty'] = $detail->subTextileOrderDetail->order_qty ?? null;
                    $detail['batch_qty'] = $detail->subDyeingBatchDetail->batch_weight ?? null;
                    $detail['fabric_description'] = $detail->subTextileOrderDetail->fabric_description ?? null;

                    return $detail;
                });

            return response()->json([
                'data' => $subDyeingGoodsDelivery,
                'message' => 'Data stored successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingGoodsDeliveryRequest $request
     * @param SubDyeingGoodsDelivery $subDyeingGoodsDelivery
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        SubDyeingGoodsDeliveryRequest $request,
        SubDyeingGoodsDelivery        $subDyeingGoodsDelivery
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $subDyeingGoodsDelivery->fill($request->all())->save();
            $subDyeingGoodsDelivery['change_details'] = $this->updateDetails($request);

            DB::commit();

            $this->notify($subDyeingGoodsDelivery, 'updated');

            return response()->json([
                'data' => $subDyeingGoodsDelivery,
                'message' => 'Data updated successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDetails(Request $request): array
    {
        $dirtyFields = [];
        $detailsForm = $request->get('details');

        foreach ($detailsForm as $detail) {
            $deliveryDetail = SubDyeingGoodsDeliveryDetail::query()->find($detail['id']);
            $deliveryDetail->fill($detail)->save();
            $dirtyFields[] = collect($deliveryDetail->getChanges())->except(['updated_at'])->keys()->join(', ');
        }

        return collect($dirtyFields)->unique()->values()->toArray();
    }

    /**
     * @param SubDyeingGoodsDelivery $subDyeingGoodsDelivery
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingGoodsDelivery $subDyeingGoodsDelivery): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingGoodsDelivery->subDyeingGoodsDeliveryDetails()->delete();
            $subDyeingGoodsDelivery->delete();
            DB::commit();

            $this->notify($subDyeingGoodsDelivery, 'deleted');

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function gatePassView($id)
    {
        $dyeingGoodsDelivery = SubDyeingGoodsDelivery::query()
            ->with([
                'buyer',
                'subDyeingGoodsDeliveryDetails',
            ])
            ->withSum('subDyeingGoodsDeliveryDetails as total_roll_sum', 'total_roll')
            ->withSum('subDyeingGoodsDeliveryDetails as delivery_qty_sum', 'delivery_qty')
            ->findOrFail($id);

        return view('subcontract::textile_module.sub-dyeing-goods-delivery.gate-pass.view', [
            'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
        ]);
    }

    public function gatePassViewPdf($id)
    {
        $dyeingGoodsDelivery = SubDyeingGoodsDelivery::query()
            ->with([
                'buyer',
                'subDyeingGoodsDeliveryDetails',
            ])
            ->withSum('subDyeingGoodsDeliveryDetails as total_roll_sum', 'total_roll')
            ->withSum('subDyeingGoodsDeliveryDetails as delivery_qty_sum', 'delivery_qty')
            ->findOrFail($id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.sub-dyeing-goods-delivery.gate-pass.pdf', [
                'isPdf' => true,
                'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("gate-pass.pdf");
    }

    public function gateChallanPassView($id)
    {
        $dyeingGoodsDelivery = SubDyeingGoodsDelivery::query()
            ->with([
                'buyer',
                'subDyeingGoodsDeliveryDetails.fabricType',
                'subDyeingGoodsDeliveryDetails.subDyeingBatchDetail',
                'subDyeingGoodsDeliveryDetails.fabricComposition',
            ])
            ->findOrFail($id);

        return view('subcontract::textile_module.sub-dyeing-goods-delivery.challan-and-gate-pass.view', [
            'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
        ]);
    }

    public function gateChallanPassPdf($id)
    {
        $dyeingGoodsDelivery = SubDyeingGoodsDelivery::query()
            ->with([
                'buyer',
                'subDyeingGoodsDeliveryDetails.fabricType',
                'subDyeingGoodsDeliveryDetails.subDyeingBatchDetail',
            ])
            ->findOrFail($id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.sub-dyeing-goods-delivery.challan-and-gate-pass.pdf', [
                'isPdf' => true,
                'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("challan-and-gate-pass.pdf");
    }

    public function billView($id)
    {
        $dyeingGoodsDelivery = SubDyeingGoodsDelivery::query()
            ->with([
                'buyer',
                'subDyeingGoodsDeliveryDetails.fabricType',
                'subDyeingGoodsDeliveryDetails.subDyeingBatchDetail',
            ])
            ->findOrFail($id);

        return view('subcontract::textile_module.sub-dyeing-goods-delivery.bill.view', [
            'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
        ]);
    }

    public function billViewPdf($id)
    {
        $dyeingGoodsDelivery = SubDyeingGoodsDelivery::query()
            ->with([
                'buyer',
                'subDyeingGoodsDeliveryDetails.fabricType',
                'subDyeingGoodsDeliveryDetails.subDyeingBatchDetail',
            ])
            ->findOrFail($id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.sub-dyeing-goods-delivery.bill.pdf', [
                'isPdf' => true,
                'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("sub-dyeing-goods-delivery-bill.pdf");
    }

    private function notify($data, $type)
    {
        (new SubGoodsDeliveryNotifyService())
            ->setData($data)
            ->setType($type)
            ->notify();
    }
}
