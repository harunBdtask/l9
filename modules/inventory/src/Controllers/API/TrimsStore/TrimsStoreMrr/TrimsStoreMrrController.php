<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr;

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
use SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreMrr\TrimsStoreMrrAction;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\TrimsStoreMrrReceiveReportExport;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreMrr\TrimsStoreMrrFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreMrrController extends Controller
{
    public function index(Request $request)
    {
        $trimsMrr = TrimsStoreMrr::query()
            ->with([
                'factory',
                'buyer',
                'store',
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


        return view('inventory::trims-store.mrr.index', [
            'trimsMrr' => $trimsMrr,
            'buyers' => $buyers,
            'factories' => $factories,
        ]);
    }


    public function create()
    {
        return view('inventory::trims-store.mrr.create');
    }

    public function store(
        TrimsStoreMrrFormRequest $request,
        TrimsStoreMrrAction      $action,
        TrimsStoreMrr            $mrr): JsonResponse
    {
        try {
            DB::beginTransaction();
            $mrr->fill($request->all())->save();
            $action->storeDetails($mrr);
            DB::commit();

            return response()->json([
                'message' => 'Trims MRR Stored Successfully',
                'data' => $mrr,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsStoreMrr $mrr
     * @return JsonResponse
     */
    public function edit(TrimsStoreMrr $mrr): JsonResponse
    {
        try {
            $mrr['address'] = $mrr->booking->location;
            $mrr['short_access_amount'] = format($mrr->booking_amount - $mrr->details->sum('amount'), 4);

            return response()->json([
                'message' => 'Fetch Trims MRR Successfully',
                'data' => $mrr,
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

    public function update(TrimsStoreMrrFormRequest $request, TrimsStoreMrr $mrr): JsonResponse
    {
        try {
            $mrr->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims MRR Stored Successfully',
                'data' => $mrr,
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
     * @param TrimsStoreMrr $mrr
     * @return RedirectResponse
     */
    public function destroy(TrimsStoreMrr $mrr): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $mrr->details()->delete();
            $mrr->delete();
            DB::commit();
            Session::flash('success', 'Trims MRR Deleted Successfully');
        } catch (\Throwable $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

    public function view(TrimsStoreMrr $mrr)
    {
        $mrr->load([
            'details.color',
            'details.itemGroup',
            'details.uom',
            'buyer',
            'store'
        ]);

        return view('inventory::trims-store.mrr.view',[
            'mrr' => $mrr
        ]);
    }

    public function pdf(TrimsStoreMrr $mrr)
    {
        $mrr->load([
            'details.color',
            'details.itemGroup',
            'details.uom',
            'buyer',
            'store'
        ]);

        $signature = ReportSignatureService::getSignatures("TRIMS STORE MRR VIEW", $mrr->buyer_id);
        $createdAt = $mrr->created_at ?? date('Y-m-d');
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::trims-store.mrr.pdf', [
                'mrr' => $mrr,
                'signature' => $signature,
                'date_time' => $dateTime,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("mrr.pdf");
    }

    public function excel(TrimsStoreMrr $mrr)
    {
        $mrr->load([
            'details.color',
            'details.itemGroup',
            'details.uom',
            'buyer',
            'store'
        ]);

        return Excel::download(new TrimsStoreMrrReceiveReportExport($mrr), 'trims_store_mrr_report.xlsx');
    }
}
