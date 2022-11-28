<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Finishingdroplets\DTO\PackingListDTO;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\PackingListV2Export;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\PnPackingList;
use SkylarkSoft\GoRMG\Finishingdroplets\Requests\PackingListV2Request;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingListUID;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;
use Throwable;


class ErpPackingListControllerV2 extends Controller
{

    public function index()
    {
        $search = request('search') ?? null;
        $packingList = PnPackingList::query()
            ->select('pn_packing_list.*', DB::raw("SUM(qty_per_carton) as total_carton"),
                DB::raw("SUM(size_wise_qty) as total_qty"),
                DB::raw("SUM(m3_cbu) as total_cbu"))
            ->with([
                'buyer',
                'purchaseOrder',
                'order',
                'CreatedByUser',
            ])
            ->groupBy('uid')
            ->orderBy('uid', 'desc')
            ->paginate();

        return view('finishingdroplets::erp-packing-list-v2.index', [
            'packingList' => $packingList
        ]);
    }

    public function create()
    {
        return view('finishingdroplets::erp-packing-list-v2.create');
    }

    public function getPo(Request $request): JsonResponse
    {
        $po = PurchaseOrder::query()
            ->with('country')
            ->where('order_id', $request->get('style_name'))
            ->get()->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'text' => $collection->po_no,
                    'country' => $collection->country->name
                ];
            });

        return response()->json($po);
    }

    /**
     * @param PackingListDTO $packingListDTO
     * @param Request $request
     * @return JsonResponse
     */
    public function getPoDetails(PackingListDTO $packingListDTO, Request $request): JsonResponse
    {
        $poDetails = $packingListDTO
            ->setPoId($request->get('po_no'))
            ->getPoDetails();

        $data = [
            'sizes' => $poDetails->getSizes(),
            'poDetails' => $poDetails->format(),
        ];

        return response()->json($data);

    }

    /**
     * @param PackingListV2Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(PackingListV2Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = [];
            $uid = PackingListUID::generateUniqueId();
            foreach ($request->get('details') as $detail) {

                foreach ($detail['sizes'] as $size) {

                    $data[] = [
                        'uid' => $uid,
                        'production_date' => $request->get('date'),
                        'buyer_id' => $detail['buyer_id'],
                        'order_id' => $detail['order_id'],
                        'purchase_order_id' => $detail['purchase_order_id'],
                        'color_id' => $detail['color_id'],
                        'size_id' => $size['size_id'],
                        'size_wise_qty' => $size['qty'],
                        'destination' => $detail['destination'],
                        'tag_type' => $detail['tag_type'],
                        'no_of_carton' => $detail['no_of_carton'],
                        'qty_per_carton' => $detail['qty_per_carton'],
                        'no_of_boxes' => $detail['no_of_boxes'],
                        'blister_kit_carton' => $detail['blister_kit_carton'],
                        'kit_bc_carton' => $detail['kit_bc_carton'],
                        'carton_no_from' => $detail['carton_no_from'],
                        'carton_no_to' => $detail['carton_no_to'],
                        'measurement_l' => $detail['measurement_l'],
                        'measurement_w' => $detail['measurement_w'],
                        'measurement_h' => $detail['measurement_h'],
                        'bc_height' => $detail['bc_height'],
                        'gw_box_weight' => $detail['gw_box_weight'],
                        'bc_gw' => $detail['bc_gw'],
                        'nw_box_weight' => $detail['nw_box_weight'],
                        'bc_nw' => $detail['bc_nw'],
                        'm3_cbu' => $detail['m3_cbu'],
                        'type_of_shipment' => $detail['type_of_shipment'],
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];

                }
            }

            PnPackingList::query()->insert($data);
            DB::commit();

            return response()->json([
                'uid' => $uid,
                'message' => 'Successfully Created',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param PackingListDTO $packingListDTO
     * @param $uid
     * @return JsonResponse
     */
    public function getEditPoDetails(PackingListDTO $packingListDTO, $uid): JsonResponse
    {
        $packingList = PnPackingList::query()
            ->with(['buyer', 'order', 'purchaseOrder', 'color'])
            ->where('uid', $uid)
            ->get();

        $productionDate = $packingList->first()->production_date;

        $packingList = $packingListDTO
            ->setPoDetails($packingList);

        $data = [
            'date' => $productionDate,
            'sizes' => $packingList->getSizes(),
            'poDetails' => $packingList->editFormat(),
        ];

        return response()->json($data);
    }

    public function view(PackingListDTO $packingListDTO, $uid)
    {
        $packingList = PnPackingList::query()
            ->with(['buyer', 'order', 'purchaseOrder', 'color'])
            ->where('uid', $uid)
            ->get();

        $productionDate = $packingList->first()->production_date;

        $packingList = $packingListDTO
            ->setPoDetails($packingList);

        return view('finishingdroplets::erp-packing-list-v2.view', [
            'date' => $productionDate,
            'sizes' => $packingList->getSizes(),
            'poDetails' => $packingList->editFormat(),
            'uid' => $uid,
        ]);
    }

    /**
     * @param PackingListDTO $packingListDTO
     * @param $uid
     * @return mixed
     */
    public function pdf(PackingListDTO $packingListDTO, $uid)
    {
        $packingList = PnPackingList::query()
            ->with(['buyer', 'order', 'purchaseOrder', 'color'])
            ->where('uid', $uid)
            ->get();

        $productionDate = $packingList->first()->production_date;

        $packingList = $packingListDTO
            ->setPoDetails($packingList);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('finishingdroplets::erp-packing-list-v2.pdf', [
                'date' => $productionDate,
                'sizes' => $packingList->getSizes(),
                'poDetails' => $packingList->editFormat(),
                'uid' => $uid,
            ])
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream($uid . '_po_list.pdf');
    }

    public function excel(PackingListDTO $packingListDTO, $uid)
    {
        $packingList = PnPackingList::query()
            ->with(['buyer', 'order', 'purchaseOrder', 'color'])
            ->where('uid', $uid)
            ->get();

        $productionDate = $packingList->first()->production_date;

        $packingList = $packingListDTO
            ->setPoDetails($packingList);

        $viewData = [
            'date' => $productionDate,
            'sizes' => $packingList->getSizes(),
            'poDetails' => $packingList->editFormat(),
            'uid' => $uid,
        ];

        return Excel::download(new PackingListV2Export($viewData), 'packing_list_v2.xlsx');
    }

    /**
     * @param PackingListV2Request $request
     * @param $uid
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(PackingListV2Request $request, $uid): JsonResponse
    {
        $packingList = $request->get('details');

        try {
            DB::beginTransaction();
            foreach ($packingList as $packing) {
                foreach ($packing['sizes'] as $size) {

                    $packingData = array_merge($packing, [
                        'size_wise_qty' => $size['qty'],
                        'size_id' => $size['size_id'],
                        'production_date' => $request->get('date')
                    ]);

                    $packingModel = PnPackingList::query()
                        ->where([
                            'uid' => $uid,
                            'size_id' => $size['size_id'],
                            'color_id' => $packing['color_id'],
                        ])->first();
                    $packingModel->fill($packingData)->save();
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $uid
     * @return Application|RedirectResponse|Redirector
     * @throws Throwable
     */
    public function destroy($uid)
    {
        try {
            DB::beginTransaction();
            PnPackingList::query()
                ->where('uid', $uid)
                ->delete();
            DB::commit();
            return redirect('/erp-packing-list-v2');
        } catch (Exception $exception) {
            return redirect('/erp-packing-list-v2');
        }
    }
}
