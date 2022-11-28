<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\TrimsStoreInventoryReportExport;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsInventoryFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsInventoryController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::query()
            ->pluck('name', 'id')
            ->prepend('select', '0');
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('select', '0');

        $trimsInventory = TrimsInventory::query()
            ->with([
                'factory',
                'details'
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        return view('inventory::trims-store.inventory.index', [
            'trimsInventory' => $trimsInventory,
            'suppliers' => $suppliers,
            'buyers' => $buyers
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('inventory::trims-store.inventory.create');
    }

    /**
     * @param TrimsInventoryFormRequest $request
     * @param TrimsInventory $inventory
     * @return JsonResponse
     */
    public function store(TrimsInventoryFormRequest $request, TrimsInventory $inventory): JsonResponse
    {
        try {
            $inventory->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims Inventory Stored Successfully',
                'data' => $inventory,
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
     * @param TrimsInventory $inventory
     * @return JsonResponse
     */
    public function edit(TrimsInventory $inventory): JsonResponse
    {
        $inventory->load('details', 'booking.details', 'booking.supplier');

        $inventory['style_name'] = $inventory->booking->style;
        $inventory['supplier_name'] = $inventory->booking->supplier->name;
        $inventory['po_no'] = $inventory->booking->po_no;
        $inventory['address'] = $inventory->booking->location;
        $inventory['attention'] = $inventory->booking->attention;
        $inventory['dealing_merchant'] = $inventory->booking->details
            ->pluck('budget.order.dealingMerchant.screen_name')
            ->unique()->values()->join(', ');
        $inventory['season_name'] = $inventory->booking->details
            ->pluck('budget.order.season.season_name')
            ->unique()->values()->join(', ');
        $inventory['short_access_qty'] = format($inventory->booking_qty - $inventory->delivery_qty, 4);

        try {
            return response()->json([
                'message' => 'Fetch Trims Inventory Successfully',
                'data' => $inventory,
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
     * @param TrimsInventoryFormRequest $request
     * @param TrimsInventory $inventory
     * @return JsonResponse
     */
    public function update(TrimsInventoryFormRequest $request, TrimsInventory $inventory): JsonResponse
    {
        try {
            $inventory->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims Inventory Stored Successfully',
                'data' => $inventory,
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
     * @param TrimsInventory $inventory
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(TrimsInventory $inventory): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $inventory->details()->delete();
            $inventory->delete();
            DB::commit();
            Session::flash('success', 'Trims Inventory Deleted Successfully');
        } catch (Exception $e) {
            DB::commit();
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function view($id)
    {
        $inventory = TrimsInventory::query()
            ->with([
                'details.itemGroup',
                'store',
                'booking.supplier',
                'booking.details',
            ])
            ->findOrFail($id);

        return view('inventory::trims-store.inventory.view', [
            'inventory' => $inventory,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function pdf($id)
    {
        $inventory = TrimsInventory::query()
            ->with([
                'details.itemGroup',
                'store',
                'booking.supplier',
                'booking.details',
            ])
            ->findOrFail($id);

        $signature = ReportSignatureService::getSignatures("TRIMS STORE INVENTORY VIEW", $inventory->buyer_id);
        $createdAt = $inventory->created_at ?? date('Y-m-d');
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::trims-store.inventory.pdf', [
                'inventory' => $inventory,
                'signature' => $signature,
                'date_time' => $dateTime,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("trims_inventory.pdf");
    }

    public function excel($id)
    {
        $inventory = TrimsInventory::query()
            ->with([
                'details.itemGroup',
                'store',
                'booking.supplier',
                'booking.details',
            ])
            ->findOrFail($id);

        return Excel::download(new TrimsStoreInventoryReportExport($inventory), 'trims_inventory_report.xlsx');
    }

    /**
     * @return JsonResponse
     */
    public function getBookingNos(): JsonResponse
    {
        try {
            $bookingNos = TrimsBooking::query()->latest()->pluck('unique_id');
            return response()->json($bookingNos, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getBuyers(): JsonResponse
    {
        try {
            $buyers = Buyer::query()->get(['id', 'name as text']);
            return response()->json($buyers, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
