<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use Exception;
use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Merchandising\Actions\BudgetNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\BudgetUnApprovalRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\POUnApproveRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers\BudgetChartService;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Library\Services\Validation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Features;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use Illuminate\Contracts\Foundation\Application;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Requests\BudgetRequest;
use SkylarkSoft\GoRMG\Merchandising\Actions\BudgetFilterFormat;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Actions\FeatureVersionAction;
use SkylarkSoft\GoRMG\Merchandising\Exception\SaveFailureException;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Budgets\BudgetCostingTemplate;
use SkylarkSoft\GoRMG\Merchandising\Requests\Budgets\TrimsCostingRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\UserWiseApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\AssociateVersionWithOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBookingDetail;
use SkylarkSoft\GoRMG\Merchandising\Actions\CopyPriceQuotationCostingsForBudget;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrderDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetailsBreakdown;

class BudgetController extends Controller
{
    public function index()
    {

        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedBudgets = 15;
        $budgets = Budget::query()
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->with([
                'productDepartment',
                'order',
                'buyer:id,name',
                'factory:id,factory_name,factory_short_name',
                'createdBy:id,screen_name'
            ])
            ->when(request('type') == 'Approved Budgets', function ($query) {
                $query->where('is_approve', 1);
            })
            ->when(request('type') == 'UnApproved Budgets', function ($query) {
                $query->where('is_approve', 0)->orWhereNull('is_approve');
            })
            ->withSum('purchaseOrders as total_po_quantity', 'po_quantity')
            ->orderBy('id', 'desc')
            ->paginate($paginateNumber);
        $search = null;

        $chartService = new BudgetChartService();

        $dashboardOverview = $chartService->dashboardOverview();

        return view('merchandising::budget.index', compact(
            'search',
            'chartService',
            'budgets',
            'dashboardOverview',
            'paginateNumber',
            'searchedBudgets'));
    }

    public function edit(Request $request)
    {
        return view('merchandising::budget.create_update');
    }

    public function get($id): JsonResponse
    {
        $budget = Budget::with('buyer', 'factory')->findOrFail($id);

        return response()->json([
            'status' => 'Success',
            'type' => 'Budget Data',
            'data' => $budget,
        ]);
    }

    public function createOrUpdate($any = 'any')
    {
        return view('merchandising::budget.create_update');
    }

    /**
     * @param BudgetRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function save(BudgetRequest $request): JsonResponse
    {
        try {
            $data = $request->except('image', 'file', 'factory', 'buyer');
            DB::beginTransaction();

            // Finding The Budget if exit
            $edit_mode = false;
            $budget = Budget::query()->where('job_no', $request->get('job_no'))
                ->where('factory_id', $request->get('factory_id'))
                ->where('buyer_id', $request->get('buyer_id'))
                ->first();

            // Uploading File
            if ($request->get('file')) {
                if (isset($budget->file)) {
                    FileUploadRemoveService::removeFile($budget->file);
                }
                $data['file'] = FileUploadRemoveService::fileUpload('budgets', $request->get('file'), 'application');
            }
            if ($budget) {
                $budget->update($data);
                $edit_mode = true;
                BudgetNotification::send($budget);
            } else {
                $budget = Budget::query()->create($data);
                AssociateVersionWithOrder::attach($budget['id'], $budget['copy_from_id']);
                // Copy Price Quotation if exist
                CopyPriceQuotationCostingsForBudget::handle($request->get('costing_details'), $budget['id']);
            }

            if ($request->get('image') && strpos($request->get('image'), 'image') !== false &&
                strpos($request->get('image'), 'base64') !== false) {
                $image_path = FileUploadRemoveService::fileUpload('budgets', $request->get('image'), 'image');
                if ($edit_mode && Storage::disk('public')->exists($budget->images)) {
                    Storage::delete($budget->images);
                }
                $budget->update(['image' => $image_path]);
            } else {
                if ($request->get('image')) {
                    $budget->update(['image' => $request->get('image')]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'Success',
                'type' => 'JOB Data',
                'code' => Response::HTTP_OK,
                'data' => $budget,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'type' => 'JOB Data',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'data' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeFile($id, $type): JsonResponse
    {
        try {
            $budget = Budget::query()->findOrFail($id);
            if ($type == 'image') {
                FileUploadRemoveService::removeFile($budget->image);
                $budget->update([
                    'image' => null,
                ]);
            } else {
                FileUploadRemoveService::removeFile($budget->file);
                $budget->update([
                    'file' => null,
                ]);
            }

            return response()->json([
                'status' => 'Success',
                'type' => 'File Deleted',
                'message' => 'File Removed Successfully',
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'type' => 'File Delete Failed',
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function search(Request $request, BudgetFilterFormat $budgetFilterFormat)
    {
        $search = $request->get('search');
        $sort = request('sort') ?? 'desc';
        $paginateNumber = request('paginateNumber') ?? 15;
        $page = request('page');
        $budgets = $budgetFilterFormat->handle($search, $paginateNumber, $sort, $page);
        $searchedBudgets = ($budgets->total());

        $totalBudgets = Budget::all()->count();

        $dashboardOverview = [
            "Total Budgets" => $totalBudgets
        ];


        return view('merchandising::budget.index', compact('search', 'budgets', 'dashboardOverview', 'paginateNumber', 'searchedBudgets'));
    }

    /**
     * @param $id
     * @return Application|RedirectResponse|Redirector
     * @throws Throwable
     */
    public function delete($id)
    {
        //Delete item if Budget id/Job no is not exist in related tables
        $validate = Validation::checkAll(Budget::find($id)->job_no, array(
                [FabricBookingDetailsBreakdown::class, 'job_no'],
                [ShortFabricBookingDetailsBreakdown::class, 'job_no'],
                [TrimsBookingDetails::class, 'budget_unique_id'],
                [ShortTrimsBookingDetails::class, 'budget_unique_id'],
                [EmbellishmentWorkOrderDetails::class, 'budget_unique_id']
            )
        );
        $validate2 = Validation::check($id, [FabricServiceBookingDetail::class, 'budget_id']);
        if (!$validate || !$validate2) {
            Session::flash('error', 'You Can Not Delete This Item!');
            return back();
        }

        try {
            DB::beginTransaction();
            Budget::query()->find($id)->delete();
            AssociateVersionWithOrder::detach($id);
            DB::commit();
            Session::flash('success', 'Data Deleted Successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        }

        return redirect('/budgets');
    }

    public function saveTrimsBudget($budgetId, TrimsCostingRequest $request): JsonResponse
    {
        $request->merge(['factory_id' => factoryId()]);
        $budget = BudgetCostingDetails::query()->firstOrNew([
            'type' => 'trims_costing',
            'budget_id' => $budgetId,
        ]);

        $budget->details = $request->all('details', 'calculation');
        $budget->save();
        if ($request->get('is_template')) {
            $budgetTemplate = BudgetCostingTemplate::query()->firstOrNew([
                'type' => 'trims_costing',
                'factory_id' => factoryId(),
                'buyer_id' => $request->get('buyer_id'),
                'template_name' => $request->get('template_name'),
            ]);
            $budgetTemplate->details = $request->all('details', 'calculation');
            $budgetTemplate->save();
        }


        return response()->json(['message' => 'Successfully Saved']);
    }

    /**
     * @throws SaveFailureException
     */
    public function saveTrimsBreakdown($budgetId, Request $request): JsonResponse
    {
        $idx = $request->input('idx');

        $trimsBudget = BudgetCostingDetails::query()->where([
            'type' => 'trims_costing',
            'budget_id' => $budgetId,
        ])->first();

        if (!$trimsBudget) {
            throw new SaveFailureException('Trims budget is not saved yet!');
        }

        try {
            $details = $trimsBudget->details->details;
            $details[$idx] = $request->details;
            $trimsBudget->details = $details;
            $trimsBudget->save();

            return response()->json(['message' => 'Successfully Updated!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }


}
