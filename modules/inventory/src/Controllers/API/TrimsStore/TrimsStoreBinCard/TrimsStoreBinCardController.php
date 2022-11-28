<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreBinCard\TrimsStoreBinCardAction;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\TrimsStoreBinCardReportExport;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCard;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreBinCardController extends Controller
{

    public function index(Request $request)
    {
        $binCards = TrimsStoreBinCard::query()
            ->with([
                'factory',
                'trimsStoreMRR',
            ])
            ->search($request)
            ->orderBy('id', 'desc')
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('select', '0');

        return view('inventory::trims-store.bin-card.index', [
            'binCards' => $binCards,
            'factories' => $factories,
        ]);
    }

    public function create()
    {
        return view('inventory::trims-store.bin-card.create');
    }

    /**
     * @param TrimsStoreBinCardFormRequest $request
     * @param TrimsStoreBinCard $binCard
     * @param TrimsStoreBinCardAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        TrimsStoreBinCardFormRequest $request,
        TrimsStoreBinCard            $binCard,
        TrimsStoreBinCardAction      $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $binCard->fill($request->all())->save();
            $action->storeDetails($binCard);
            DB::commit();

            return response()->json([
                'message' => 'Trims Bin Card Stored Successfully',
                'data' => $binCard,
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
     * @param TrimsStoreBinCard $binCard
     * @return JsonResponse
     */
    public function edit(TrimsStoreBinCard $binCard): JsonResponse
    {
        try {
            $binCard->load('details', 'booking.details', 'booking.supplier');

            $binCard['style_name'] = $binCard->booking->style;
            $binCard['supplier_name'] = $binCard->booking->supplier->name;
            $binCard['po_no'] = $binCard->booking->po_no;
            $binCard['address'] = $binCard->booking->location;
            $binCard['attention'] = $binCard->booking->attention;
            $binCard['dealing_merchant'] = $binCard->booking->details
                ->pluck('budget.order.dealingMerchant.screen_name')
                ->unique()->values()->join(', ');
            $binCard['season_name'] = $binCard->booking->details
                ->pluck('budget.order.season.season_name')
                ->unique()->values()->join(', ');
            $binCard['short_access_qty'] = format($binCard->booking_qty - $binCard->details->sum('issue_qty'), 4);

            return response()->json([
                'message' => 'Fetch Trims Bin Card Successfully',
                'data' => $binCard,
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
     * @param TrimsStoreBinCardFormRequest $request
     * @param TrimsStoreBinCard $binCard
     * @return JsonResponse
     */
    public function update(
        TrimsStoreBinCardFormRequest $request,
        TrimsStoreBinCard            $binCard
    ): JsonResponse {
        try {
            $binCard->fill($request->all())->save();
            return response()->json([
                'message' => 'Trims Bin Card Update Successfully',
                'data' => $binCard,
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
     * @param TrimsStoreBinCard $binCard
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(TrimsStoreBinCard $binCard): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $binCard->details()->delete();
            $binCard->delete();
            DB::commit();
            Session::flash('success', 'Trims Receive Deleted Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

    /**
     * @param TrimsStoreBinCard $binCard
     * @return Application|Factory|View
     */
    public function view(TrimsStoreBinCard $binCard)
    {
        $binCard->load([
            'details',
            'store',
            'booking.supplier',
            'booking.details',
            'details.floor',
            'details.itemGroup',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.mrrDetail',
        ]);

        return view('inventory::trims-store.bin-card.view', [
            'binCard' => $binCard,
        ]);
    }

    /**
     * @param TrimsStoreBinCard $binCard
     * @return mixed
     */
    public function pdf(TrimsStoreBinCard $binCard)
    {
        $binCard->load([
            'details',
            'store',
            'booking.supplier',
            'details.itemGroup',
            'booking.details',
            'details.floor',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.mrrDetail',
        ]);

        $signature = ReportSignatureService::getSignatures("TRIMS STORE BIN CARD VIEW", $binCard->buyer_id);
        $createdAt = $binCard->created_at ?? date('Y-m-d');
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::trims-store.bin-card.pdf', [
                'binCard' => $binCard,
                'signature' => $signature,
                'date_time' => $dateTime,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("bin_card.pdf");
    }

    public function excel(TrimsStoreBinCard $binCard)
    {
        $binCard->load([
            'details',
            'store',
            'booking.supplier',
            'details.itemGroup',
            'booking.details',
            'details.floor',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.mrrDetail',
        ]);

        return Excel::download((new TrimsStoreBinCardReportExport($binCard)), 'trims_store_bincard_report.xlsx');
    }
}
