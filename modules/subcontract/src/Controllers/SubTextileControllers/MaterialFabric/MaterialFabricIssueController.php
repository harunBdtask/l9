<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Actions\FabricIssueAction;
use SkylarkSoft\GoRMG\Subcontract\Actions\StockSummaryAction;
use SkylarkSoft\GoRMG\Subcontract\Actions\SyncFabricIssueDetails;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FabricIssueFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MaterialFabricIssueController extends Controller
{
    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select', 0);
        $stores = SubContractGreyStore::query()->pluck('name', 'id')->prepend('Select', 0);
        $issuePurpose = collect(SubGreyStoreIssue::ISSUE_PURPOSE)->prepend('Select', 0);
        $subGreyStoreIssues = SubGreyStoreIssue::query()->search($request)->with([
            'factory',
            'supplier',
            'textileOrder',
        ])->orderBy('id', 'desc')->paginate();

        $supplier = Buyer::query()
            ->where('party_type', 'Subcontract')
            ->factoryFilter()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view('subcontract::textile_module.material-fabric.issue.index', [
            'subGreyStoreIssues' => $subGreyStoreIssues,
            'issuePurpose' => $issuePurpose,
            'factories' => $factories,
            'stores' => $stores,
            'supplier' => $supplier,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view('subcontract::textile_module.material-fabric.issue.form');
    }

    /**
     * @throws Throwable
     */
    public function store(
        FabricIssueFormRequest $request,
        SubGreyStoreIssue      $greyStoreIssue,
        FabricIssueAction      $fabricIssueAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $greyStoreIssue->fill($request->all())->save();
            $fabricIssueAction->attach($greyStoreIssue);
            DB::commit();

            return response()->json([
                'message' => 'Fabric issue created successfully',
                'data' => $greyStoreIssue,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(SubGreyStoreIssue $greyStoreIssue): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fabric issue fetch successfully',
                'data' => $greyStoreIssue->load('issueDetails'),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricIssueFormRequest $request
     * @param SubGreyStoreIssue $greyStoreIssue
     * @param SyncFabricIssueDetails $syncFabricIssueDetails
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        FabricIssueFormRequest $request,
        SubGreyStoreIssue      $greyStoreIssue,
        SyncFabricIssueDetails $syncFabricIssueDetails
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $greyStoreIssue->fill($request->all())->save();
            $syncFabricIssueDetails->handle($greyStoreIssue);
            DB::commit();

            return response()->json([
                'message' => 'Fabric issue updated successfully',
                'data' => $greyStoreIssue,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(SubGreyStoreIssue $greyStoreIssue, StockSummaryAction $stockSummaryAction): RedirectResponse
    {
        try {
            DB::beginTransaction();
            foreach ($greyStoreIssue->issueDetails as $issueDetail) {
                $issueDetail->delete();
                $stockSummaryAction->attachToStockSummaryReport($issueDetail);
                $stockSummaryAction->attachToDailyStockSummaryReport($issueDetail);
            }
            $greyStoreIssue->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
