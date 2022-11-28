<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue;

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
use SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreIssue\TrimsStoreIssueAction;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\TrimsStoreIssueReportExport;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssue;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreIssue\TrimsStoreIssueFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreIssueController extends Controller
{

    public function index(Request $request)
    {
        $trimsIssues = TrimsStoreIssue::query()
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

        return view('inventory::trims-store.trims-issue.index', [
            'trimsIssues' => $trimsIssues,
            'buyers' => $buyers,
            'factories' => $factories
        ]);
    }


    public function create()
    {
        return view('inventory::trims-store.trims-issue.create');
    }

    /**
     * @param TrimsStoreIssueFormRequest $request
     * @param TrimsStoreIssueAction $action
     * @param TrimsStoreIssue $issue
     * @return JsonResponse
     */
    public function store(
        TrimsStoreIssueFormRequest $request,
        TrimsStoreIssueAction      $action,
        TrimsStoreIssue            $issue
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $issue->fill($request->all())->save();
            $action->storeDetails($issue);
            DB::commit();

            return response()->json([
                'message' => 'Trims Issue Stored Successfully',
                'data' => $issue,
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
     * @param TrimsStoreIssue $issue
     * @return JsonResponse
     */
    public function edit(TrimsStoreIssue $issue): JsonResponse
    {
        $issue->load('details', 'booking');
        $issue['short_access_qty'] = format($issue->booking_qty - $issue->details->sum('issue_qty'), 4);

        try {
            $issue['address'] = $issue->booking->location;

            return response()->json([
                'message' => 'Fetch Trims Issue Successfully',
                'data' => $issue,
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
     * @param TrimsStoreIssueFormRequest $request
     * @param TrimsStoreIssue $issue
     * @return JsonResponse
     */
    public function update(TrimsStoreIssueFormRequest $request, TrimsStoreIssue $issue): JsonResponse
    {
        try {
            $issue->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims Issue Stored Successfully',
                'data' => $issue,
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
     * @param TrimsStoreIssue $issue
     * @return RedirectResponse
     */
    public function destroy(TrimsStoreIssue $issue): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $issue->details()->delete();
            $issue->delete();
            DB::commit();
            Session::flash('success', 'Trims Issue Deleted Successfully');
        } catch (\Throwable $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

    /**
     * @param TrimsStoreIssue $issue
     * @return Application|Factory|View
     */
    public function view(TrimsStoreIssue $issue)
    {
        $issue->load([
            'details',
            'store',
            'booking.supplier',
            'booking.details',
            'details.floor',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.itemGroup',
            'details.trimsBinCardDetail.mrrDetail',
        ]);

        return view('inventory::trims-store.trims-issue.view', [
            'issue' => $issue,
        ]);
    }

    /**
     * @param TrimsStoreIssue $issue
     * @return mixed
     */
    public function pdf(TrimsStoreIssue $issue)
    {
        $issue->load([
            'details',
            'store',
            'booking.supplier',
            'booking.details',
            'details.floor',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.itemGroup',
            'details.trimsBinCardDetail.mrrDetail',
        ]);

        $signature = ReportSignatureService::getSignatures("TRIMS STORE ISSUE VIEW", $issue->buyer_id);
        $createdAt = $issue->created_at ?? date('Y-m-d');
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::trims-store.trims-issue.pdf', [
                'issue' => $issue,
                'signature' => $signature,
                'date_time' => $dateTime,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("trims_store_issue.pdf");
    }

    public function excel(TrimsStoreIssue $issue)
    {
        $issue->load([
            'details',
            'store',
            'booking.supplier',
            'booking.details',
            'details.floor',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.itemGroup',
            'details.trimsBinCardDetail.mrrDetail',
        ]);

        return Excel::download(new TrimsStoreIssueReportExport($issue),'trims_store_issue_report.xlsx');
    }
}
