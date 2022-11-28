<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreDeliveryChallan;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallanDetailsAction;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\TrimsStoreDeliveryChallanReportExport;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallan;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallanDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsDeliveryChallan\TrimsStoreDeliveryChallanFormRequest;
use SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters\TrimsStoreDeliveryChallanFormatter;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\BookingDataApiService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreDeliveryChallanController extends Controller
{
    public function index(Request $request)
    {
        $trimsDeliveryChallans = TrimsStoreDeliveryChallan::query()
            ->with([
                'factory:id,factory_name',
                'buyer:id,name',
                'store:id,name',
            ])
            ->search($request)
            ->latest()
            ->paginate();

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('select', '0');

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('select', '0');

        $trimsDeliveryChallans->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'challan_no' => $item->challan_no,
                'factory' => $item->factory->factory_name ?? '',
                'buyer' => $item->buyer->name ?? '',
                'store' => $item->store->name ?? '',
                'challan_date' => $item->challan_date,
                'challan_qty' => $item->challan_qty,
                'challan_type' => $item->challan_type,
                'booking_no' => $item->booking_no,
                'booking_qty' => $item->booking_qty,
                'item_description' => collect($item->details)->pluck('item_description')->unique()->values()->join(', '),
            ];
        });

        return view('inventory::trims-store.trims-delivery-challan.index', [
            'trimsDeliveryChallans' => $trimsDeliveryChallans,
            'buyers' => $buyers,
            'factories' => $factories,
        ]);
    }


    public function create()
    {
        return view('inventory::trims-store.trims-delivery-challan.create');
    }

    /**
     * @param TrimsStoreDeliveryChallanFormRequest $request
     * @param TrimsStoreDeliveryChallanDetailsAction $action
     * @return JsonResponse
     */
    public function store(
        TrimsStoreDeliveryChallanFormRequest   $request,
        TrimsStoreDeliveryChallanDetailsAction $action
    ): JsonResponse
    {
        try {
            DB::beginTransaction();
            $challan = [];
            foreach ($request->get('booking_nos') as $bookingNo) {
                $bookingData = BookingDataApiService::get($bookingNo);
                $data = collect($bookingData)->merge($request)->toArray();
                $challan = TrimsStoreDeliveryChallan::query()->create($data);
                $action->storeDetails($challan);
            }
            DB::commit();

            return response()->json([
                'message' => 'Trims Delivery Challan Stored Successfully',
                'data' => $challan,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $challanNo
     * @return JsonResponse
     */
    public function edit($challanNo): JsonResponse
    {
        try {
            $challans = $this->getChallanNoWiseData($challanNo);

            return response()->json([
                'message' => 'Trims Delivery Challan Fetched Successfully',
                'data' => $challans,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param TrimsStoreDeliveryChallan $challan
     * @return JsonResponse
     */
    public function update(Request $request, TrimsStoreDeliveryChallan $challan): JsonResponse
    {
        try {
            $challan->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims Delivery Challan Stored Successfully',
                'data' => $challan,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $challanNo
     * @return Application|Factory|View
     */
    public function view($challanNo)
    {
        $deliveryChallans = $this->getChallanNoWiseData($challanNo);
        $details = $deliveryChallans->pluck('details')->flatten(1);

        return view('inventory::trims-store.trims-delivery-challan.view', compact('deliveryChallans', 'details', 'challanNo'));
    }

    /**
     * @param $challanNo
     * @return mixed
     */
    public function pdf($challanNo)
    {
        $deliveryChallans = $this->getChallanNoWiseData($challanNo);
        $details = $deliveryChallans->pluck('details')->flatten(1);
        $signature = ReportSignatureService::getSignatures("TRIMS DELIVERY CHALLAN VIEW", $deliveryChallans->first('buyer_id'));
        $createdAt = $deliveryChallans->first('created_at') ?? date('Y-m-d');
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::trims-store.trims-delivery-challan.pdf',
                [
                    'deliveryChallans' => $deliveryChallans,
                    'details' => $details,
                    'challanNo' => $challanNo,
                    'signature' => $signature,
                    'date_time' => $dateTime,
                ]
            )
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("trims_delivery_challan.pdf");
    }

    public function excel($challanNo)
    {
        $deliveryChallans = $this->getChallanNoWiseData($challanNo);
        $details = $deliveryChallans->pluck('details')->flatten(1);

        return Excel::download(new TrimsStoreDeliveryChallanReportExport($challanNo, $deliveryChallans, $details), 'trims_store_delivery_challan_report.xlsx');
    }

    /**
     * @param $challanNo
     * @return Collection
     */
    private function getChallanNoWiseData($challanNo): Collection
    {
        $relations = [
            'factory:id,factory_name',
            'buyer:id,name',
            'store:id,name',
            'details.issueDetail.issue',
            'details.itemGroup',
            'details.color',
            'details.uom',
            'details.floor',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
        ];

        $challans = TrimsStoreDeliveryChallan::query()
            ->with($relations)
            ->where('challan_no', $challanNo)
            ->get();

        return collect(TrimsStoreDeliveryChallanFormatter::format($challans));
    }

    /**
     * @param $challanNo
     * @return RedirectResponse
     */
    public function destroy($challanNo): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $challans = TrimsStoreDeliveryChallan::query()
                ->where('challan_no', $challanNo);
            TrimsStoreDeliveryChallanDetail::query()
                ->whereIn('trims_store_delivery_challan_id', $challans->pluck('id'))
                ->delete();
            $challans->delete();
            DB::commit();
            Session::flash('success', 'Trims Delivery Challan Deleted Successfully');
        } catch (Throwable $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }
}
