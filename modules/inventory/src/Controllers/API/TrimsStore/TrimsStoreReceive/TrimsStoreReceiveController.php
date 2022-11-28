<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreReceive;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreReceive\TrimsStoreReceiveAction;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\TrimsStoreReceiveReportExport;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceive;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreReceiveController extends Controller
{
    public function index(Request $request)
    {
        $trimsStoreReceive = TrimsStoreReceive::query()
            ->with([
                'factory',
                'trimsInventory',
                'buyer',
                'booking'
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('select', '0');

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('select', '0');

        return view('inventory::trims-store.trims-receive.index', [
            'trimsStoreReceive' => $trimsStoreReceive,
            'buyers' => $buyers,
            'factories' => $factories
        ]);
    }

    /**
     * @return View
     */
    public function create()
    {
        return view('inventory::trims-store.trims-receive.create');
    }

    /**
     * @param TrimsStoreReceiveFormRequest $request
     * @param TrimsStoreReceive $receive
     * @param TrimsStoreReceiveAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        TrimsStoreReceiveFormRequest $request,
        TrimsStoreReceive            $receive,
        TrimsStoreReceiveAction      $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $receive->fill($request->all())->save();
            $action->storeDetails($receive);
            DB::commit();

            return response()->json([
                'message' => 'Trims Receive Stored Successfully',
                'data' => $receive,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsStoreReceive $receive
     * @return JsonResponse
     */
    public function edit(TrimsStoreReceive $receive): JsonResponse
    {
        try {
            $receive->load('booking.supplier');

            $receive['style_name'] = $receive->booking->style;
            $receive['supplier_name'] = $receive->booking->supplier->name;
            $receive['po_no'] = $receive->booking->po_no;
            $receive['address'] = $receive->booking->location;
            $receive['attention'] = $receive->booking->attention;
            $receive['receive_qty'] = $receive->details->sum('receive_qty');
            $receive['booking_qty'] = $receive->booking->total_trims_booking_qty;
            $receive['short_access_qty'] = $receive->booking->total_trims_booking_qty - $receive->details->sum('receive_qty');
            $receive['dealing_merchant'] = $receive->booking->details
                ->pluck('budget.order.dealingMerchant.screen_name')
                ->unique()->values()->join(', ');
            $receive['season_name'] = $receive->booking->details
                ->pluck('budget.order.season.season_name')
                ->unique()->values()->join(', ');

            return response()->json([
                'message' => 'Fetch Trims Receive Successfully',
                'data' => $receive,
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
     * @param TrimsStoreReceiveFormRequest $request
     * @param TrimsStoreReceive $receive
     * @return JsonResponse
     */
    public function update(TrimsStoreReceiveFormRequest $request, TrimsStoreReceive $receive): JsonResponse
    {
        try {
            $receive->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims Receive Stored Successfully',
                'data' => $receive,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
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
     * @param TrimsStoreReceive $receive
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(TrimsStoreReceive $receive): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $receive->details()->delete();
            $receive->delete();
            DB::commit();
            Session::flash('success', 'Trims Receive Deleted Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

    public function view(TrimsStoreReceive $receive)
    {
        $receive->load([
            'buyer',
            'booking',
            'details.color',
            'details.itemGroup',
            'details.uom',
            'trimsInventory'
        ]);
        return view('inventory::trims-store.trims-receive.view',[
            'receive' => $receive
        ]);
    }

    public function pdf(TrimsStoreReceive $receive)
    {
        $receive->load([
            'buyer',
            'booking',
            'details.color',
            'details.itemGroup',
            'details.uom',
            'trimsInventory'
        ]);

        $signature = ReportSignatureService::getSignatures("TRIMS STORE RECEIVE VIEW", $receive->buyer_id);
        $createdAt = $receive->created_at ?? date('Y-m-d');
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::trims-store.trims-receive.pdf', [
                'receive' => $receive,
                'signature' => $signature,
                'date_time' => $dateTime,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("trims_receive.pdf");
    }

    public function excel(TrimsStoreReceive $receive)
    {
        $receive->load([
            'buyer',
            'booking',
            'details.color',
            'details.itemGroup',
            'details.uom',
            'trimsInventory'
        ]);

        return Excel::download(new TrimsStoreReceiveReportExport($receive),'trims_receive_report.xlsx');
    }
}
