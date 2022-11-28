<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreIssue;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\StockSummaryAction;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\TrimsStoreIssueDetailsAction;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssue;
use SkylarkSoft\GoRMG\TrimsStore\PackageConst;
use SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreIssue\TrimsStoreIssueFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreIssueController extends Controller
{
    public function index(Request $request)
    {
        $issues = TrimsStoreIssue::query()
            ->orderByDesc('id')
            ->search($request)
            ->paginate();

        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $sources = collect(TrimsStoreIssue::SOURCES)->prepend('Select', 0);

        $stores = Store::query()->pluck('name', 'id')
            ->prepend('Select', 0);

        $issueBasis = collect(TrimsStoreIssue::ISSUE_BASIS)->prepend('Select', 0);

        $payModes = collect(TrimsStoreIssue::PAY_MODES)->prepend('Select', 0);

        return view(PackageConst::VIEW_NAMESPACE . '::v3.issue.index', [
            'issues' => $issues,
            'factories' => $factories,
            'sources' => $sources,
            'stores' => $stores,
            'issueBasis' => $issueBasis,
            'payModes' => $payModes,
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_NAMESPACE . '::v3.issue.create');
    }

    /**
     * @param TrimsStoreIssueFormRequest $request
     * @param TrimsStoreIssue $issue
     * @param TrimsStoreIssueDetailsAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        TrimsStoreIssueFormRequest   $request,
        TrimsStoreIssue              $issue,
        TrimsStoreIssueDetailsAction $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $issue->fill($request->all())->save();

            if ($request->input('trims_store_receive_id')) {
                $action->storeDetails($issue);
            }
            DB::commit();

            return response()->json([
                'message' => 'Trims store issue stored successfully',
                'data' => $issue,
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
     * @param TrimsStoreIssue $issue
     * @return JsonResponse
     */
    public function edit(TrimsStoreIssue $issue): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch trims store issue successfully',
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
     * @param TrimsStoreIssue $issue
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function view(TrimsStoreIssue $issue)
    {
        $issue->load(
            'trimsStoreReceive',
            'factory:id,factory_name,factory_address',
            'store:id,name',
            'details.buyer:id,name',
            'details.currency',
            'details.uom',
            'details.floor',
            'details.supplier:id,name,address_1',
            'details.color',
            'details.size',
            'details.itemGroup',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.order:id,style_name,item_details'
        );

        return view(PackageConst::VIEW_NAMESPACE . '::v3.issue.view', compact('issue'));
    }

    /**
     * @param TrimsStoreIssue $issue
     * @return mixed
     */
    public function pdf(TrimsStoreIssue $issue)
    {
        $issue->load(
            'trimsStoreReceive',
            'factory:id,factory_name,factory_address',
            'store:id,name',
            'details.buyer:id,name',
            'details.currency',
            'details.uom',
            'details.floor',
            'details.supplier:id,name,address_1',
            'details.color',
            'details.size',
            'details.itemGroup',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.bin',
            'details.order:id,style_name,item_details'
        );

        $pdf = PDF::setOption('enable-local-file-access', true)->loadView(
            PackageConst::VIEW_NAMESPACE . '::v3.issue.pdf',
            compact('issue')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('trims_store_issue_report.pdf');
    }

    /**
     * @param TrimsStoreIssueFormRequest $request
     * @param TrimsStoreIssue $issue
     * @return JsonResponse
     */
    public function update(
        TrimsStoreIssueFormRequest $request,
        TrimsStoreIssue            $issue
    ): JsonResponse {
        try {
            $issue->fill($request->all())->save();

            return response()->json([
                'message' => 'Trims store issue updated successfully',
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
     * @param StockSummaryAction $action
     * @return RedirectResponse
     */
    public function destroy(
        TrimsStoreIssue    $issue,
        StockSummaryAction $action
    ): RedirectResponse {
        try {
            DB::beginTransaction();
            $issue->details()->each(function ($detail) use ($action) {
                $detail->delete();
                $action->attachToStockSummary($detail);
                $action->attachToDailyStockSummary($detail);
            });
            $issue->delete();
            DB::commit();
            Session::flash('success', 'Trims store issue deleted successfully');
        } catch (\Throwable $e) {
            Session::flash('success', $e->getMessage());
        } finally {
            return back();
        }
    }
}
