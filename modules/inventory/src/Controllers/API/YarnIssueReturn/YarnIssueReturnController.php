<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssueReturn;

use DB;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Rules\YarnIssueReturnQtyRule;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use Throwable;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use SkylarkSoft\GoRMG\Inventory\Requests\{YarnIssueReturnRequest, YarnIssueReturnDetailRequest};
use SkylarkSoft\GoRMG\Inventory\Services\{YarnIssueReturn\YarnIssueReturnService, YarnStockSummaryService};
use SkylarkSoft\GoRMG\Inventory\Models\{YarnIssue,
    YarnIssueDetail,
    YarnIssueReturn,
    YarnIssueReturnDetail,
    YarnReceiveDetail};

class YarnIssueReturnController extends Controller
{
    public function index(Request $request)
    {
        $issue_return_no = $request->input('issue_return_no');
        $return_challan = $request->input('return_challan');
        $company_id = $request->input('company_id');
        $return_date = $request->input('return_date');
        $company = Factory::query()->get();

        $issueReturns = YarnIssueReturn::query()
            ->with('factory')
            ->when($issue_return_no, function (Builder $builder) use ($issue_return_no) {
                $builder->where('issue_return_no', $issue_return_no);
            })
            ->when($return_challan, function (Builder $builder) use ($return_challan) {
                $builder->where('return_challan', $return_challan);
            })
            ->when($company_id, function (Builder $builder) use ($company_id) {
                $builder->where('factory_id', $company_id);
            })
            ->when($return_date, function (Builder $builder) use ($return_date) {
                $builder->where('return_date', $return_date);
            })
            ->orderByDesc('id')
            ->paginate();

        return view('inventory::yarns.issue-return.index', compact('issueReturns', 'company'));
    }

    public function create()
    {
        return view('inventory::yarns.issue-return.create');
    }

    public function yarnIssueSearch(): JsonResponse
    {
        $toDate = request('to_date');
        $fromDate = request('from_date');
        $searchNo = request('search_no');
        $searchBy = (int) request('search_by');

        $issue = YarnIssue::query()
            ->with('supplier', 'details')
            ->when($searchBy === 1, Filter::applyFilter('issue_no', $searchNo))
            ->when($searchBy === 2 || $searchBy === 3, function ($query) use ($searchBy, $searchNo) {
                $query->whereHas('details', function ($query) use ($searchBy, $searchNo) {
                    $query->when($searchBy === 2, Filter::applyFilter('demand_no', $searchNo));
                    $query->when($searchBy === 3, function ($query) use ($searchNo) {
                        $yarn = YarnReceiveDetail::query()->where('product_code', $searchNo)->first();
                        $query->where(YarnItemAction::itemCriteria($yarn));
                    });
                });
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                return $query->whereBetween('issue_date', [$fromDate, $toDate]);
            })
            ->orderByDesc('id')
            ->paginate();

        $this->response['total'] = $issue->total();
        $this->response['last_page'] = $issue->lastPage();
        $this->response['current_page'] = $issue->currentPage();
        $this->response['data'] = $issue->map(function ($issue) {
                $productCode = $issue->details->map(function($details) {
                    $productCode = YarnReceiveDetail::query()->where(YarnItemAction::itemCriteria($details))->first()->product_code ?? null;
                    $details->product_code = $productCode;
                    return $details;
                })->pluck('product_code')->implode(', ');

                return [
                    'issue_id' => $issue->id,
                    'issue_no' => $issue->issue_no,
                    'product_code' => $productCode,
                    'issue_date' => $issue->issue_date,
                    'issue_basis' => $issue->issue_basis,
                    'supplier_id' => $issue->supplier_id,
                    'supplier_name' => $issue->supplier->name,
                    'return_source' => $issue->knitting_source,
                    'issue_qty' => $issue->details->sum('issue_qty'),
                    'issue_basis_value' => $issue->issueBasisValue(),
                    'year' => Carbon::parse($issue->issue_date)->year,
                    'knitting_company' => $issue->knittingCompany()->id,
                    'return_source_value' => $issue->knittingSourceValue(),
                    'knitting_company_value' => $issue->knittingCompany()->name,
                    'requisition_no' => $issue->details->pluck('demand_no')->implode(', '),
                ];
            });

        return response()->json($this->response);
    }

    public function issueYarns(Request $request): JsonResponse
    {
        $issueId = $request->input('issue_id');

        $details = YarnIssueDetail::query()
            ->with(['store', 'floor', 'room', 'rack', 'shelf', 'bin'])
            ->where('yarn_issue_id', $issueId)
            ->orderByDesc('id')
            ->get();

        foreach ($details as $detail) {
            $summary = (new YarnStockSummaryService())->summary($detail);
            $this->response[] = [
                'yarn_issue_detail_id' => $detail->id,
                'yarn_count_id' => $detail->yarn_count_id,
                'yarn_count_value' => $detail->yarn_count->yarn_count,
                'yarn_composition_id' => $detail->yarn_composition_id,
                'yarn_composition_value' => $detail->composition->yarn_composition,
                'yarn_type_id' => $detail->yarn_type_id,
                'yarn_type_value' => $detail->type->name,
                'yarn_color' => $detail->yarn_color,
                'yarn_brand' => $detail->yarn_brand,
                'yarn_lot' => $detail->yarn_lot,
                'uom_id' => $detail->uom_id,
                'uom_value' => $detail->uom->unit_of_measurement,
                'issue_qty' => $detail->issue_qty,
                'return_qty' => (new YarnIssueReturnQtyRule())->returnQty($detail),
                'rate' => $detail->rate,
                'return_value' => $detail->return_value,
                'store_id' => $detail->store_id,
                'store_name' => $detail->store->name,
                'floor_id' => $detail->floor_id,
                'floor_name' => $detail->floor->name,
                'room_id' => $detail->room_id,
                'room_name' => $detail->room->name,
                'rack_id' => $detail->rack_id,
                'rack_name' => $detail->rack->name,
                'shelf_id' => $detail->shelf_id,
                'shelf_name' => $detail->shelf->name,
                'bin_id' => $detail->bin_id,
                'bin_name' => $detail->bin->name,
                'remarks' => $detail->remarks,
                'current_stock' => $summary->balance,
                'receive_qty' => $summary->receive_qty,
            ];
        }

        return response()->json($this->response);
    }

    /**
     * @throws Throwable
     */
    public function store(YarnIssueReturnRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $issueReturn = YarnIssueReturn::query()->findOrNew($request->input('id'));
            $issueReturn->fill($request->all());
            $issueReturn->save();

            DB::commit();
            return response()->json($issueReturn, Response::HTTP_OK);
        } catch (\Exception $exception) {
            $this->response['message'] = SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->statusCode = 500;
            DB::rollBack();
        }

        return response()->json($this->response, $this->statusCode);
    }

    /**
     * @throws Throwable
     */
    public function storeDetail(YarnIssueReturn $issueReturn, YarnIssueReturnDetailRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = new YarnIssueReturnDetail();
            $request->merge(['return_value' => $request->input('return_qty') * $request->input('rate')]);
            $data->fill($request->all());
            $data->save();

            (new YarnIssueReturnService())->created($data);

            DB::commit();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            $this->response['message'] = SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->statusCode = 500;
        }

        return response()->json($this->response, $this->statusCode);
    }

    public function delete($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $issueReturn = YarnIssueReturn::query()->findOrFail($id);

            if ($issueReturn->details()->count() != 0) {
                session()->flash('danger', 'Please delete details first!');
            } else {
                $issueReturn->delete();
                session()->flash('success', 'Successfully Deleted');
            }

            DB::commit();
        } catch (\Exception $e) {
            $this->response['message'] = E_DEL_MSG;
            $this->statusCode = 500;
            session()->flash('danger', E_DEL_MSG);
        } catch (Throwable $e) {
            $this->response['message'] = $e->getMessage();
            $this->statusCode = 500;
            session()->flash('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * @throws Throwable
     */
    public function deleteDetail(YarnIssueReturnDetail $detail): JsonResponse
    {
        try {
            DB::beginTransaction();

            (new YarnIssueReturnService())->deleted($detail);
            $detail->delete();

            $this->response['message'] = S_DEL_MSG;
            DB::commit();
        } catch (\Exception $e) {
            $this->response['message'] = E_DEL_MSG;
            $this->statusCode = 500;
        }

        return response()->json($this->response, $this->statusCode);
    }

    public function details(YarnIssueReturn $issueReturn): JsonResponse
    {
        $this->response['issue_return'] = $issueReturn;
        $this->response['details'] = $issueReturn->details()->with([
            'store', 'uom', 'floor', 'room', 'rack', 'shelf', 'composition', 'yarn_count', 'type', 'bin'
        ])
        ->orderByDesc('id')
        ->get();

        return response()->json($this->response);
    }

    public function view($id)
    {
        $data = YarnIssueReturn::with([
            'details.composition', 'details.yarn_count', 'details.type', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.store', 'details.uom'
        ])->findOrFail($id);

        return view('inventory::yarns.issue-return.view', compact('data'));
    }

    public function print($id)
    {
        $data = YarnIssueReturn::with([
            'details.composition', 'details.yarn_count', 'details.type', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.store', 'details.uom'
        ])->findOrFail($id);

        return view('inventory::yarns.issue-return.print', compact('data'));
    }
}
