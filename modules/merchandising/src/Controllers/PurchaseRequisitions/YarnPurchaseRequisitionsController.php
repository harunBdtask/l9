<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PurchaseRequisitions;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisitionDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;

class YarnPurchaseRequisitionsController extends Controller
{
    public function index()
    {
        $requisitions = YarnPurchaseRequisition::with('factory')
            ->latest()
            ->paginate();

        \request()->flash();
    }

    public function store(Request $request): JsonResponse
    {
        $this->validateYarnPurchaseRequisitionRequest($request);

        try {
            $requisition = new YarnPurchaseRequisition($request->all([
                'factory_id',
                'required_date',
                'requisition_date',
                'pay_mode',
                'source',
                'currency',
                'dealing_merchant_id',
                'attention',
                'remarks',
                'ready_to_approve',
                'unapproved_request',
                'terms_condition',
            ]));

            $requisition->save();

            return response()->json([
                'message' => 'Saved Successfully!',
                'data' => $requisition,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

            return response()->json(
                ['message' => 'Failed!', 'info' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update(YarnPurchaseRequisition $requisition, Request $request): JsonResponse
    {
        $this->validateYarnPurchaseRequisitionRequest($request);

        try {
            $requisition->update($request->all([
                'requisition_no',
                'factory_id',
                'required_date',
                'requisition_date',
                'pay_mode',
                'source',
                'currency',
                'dealing_merchant_id',
                'attention',
                'remarks',
                'ready_to_approve',
                'unapproved_request',
                'terms_condition',
            ]));

            return response()->json(['message' => 'Updated Successfully!', 'data' => $requisition], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

            return response()->json(['message' => 'Failed!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id)
    {
        $requisition = YarnPurchaseRequisition::with('details')->findOrFail($id);
        $signature = ReportSignatureService::getSignatures("YARN PURCHASE REQUISITION VIEW");
        return view('merchandising::yarn-purchase.view', compact('requisition', 'signature'));
    }

    public function pdf($id)
    {

        $requisition = YarnPurchaseRequisition::with('details')->findOrFail($id);
        $signature = ReportSignatureService::getSignatures("YARN PURCHASE REQUISITION VIEW");

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView("merchandising::yarn-purchase.pdf",compact('requisition', 'signature'))
            ->setPaper('a4')->setOrientation('landscape');

        return $pdf->stream("{$id}_yarn_purchase.pdf");
    }

    public function show(YarnPurchaseRequisition $requisition): JsonResponse
    {
        return response()->json(compact('requisition'));
    }

    public function delete(YarnPurchaseRequisition $requisition): JsonResponse
    {
        try {
            $requisition->delete();

            return response()->json(['message' => 'Deleted Successfully!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::info('YarnPurchaseRequisition Delete: ' . $e->getMessage() . ':' . $e->getLine());

            return response()->json(['message' => 'Failed!', 'info' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function validateYarnPurchaseRequisitionRequest(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'required_date' => 'required',
            'requisition_date' => 'required',
            'pay_mode' => 'required',
            'source' => 'required',
        ]);
    }

    public function budgetDetailsSearch(Request $request): JsonResponse
    {
        try {
            $buyer_id = $request->get("buyer_id");
            $style_name = $request->get("style_name");
            $uniq_id = $request->get("uniq_id");
            $style_year = $request->get("style_year");


            $budgets = Budget::with('buyer', 'fabricCosting')
                ->where("buyer_id", $buyer_id)
                ->when($style_name, function ($q) use ($style_name) {
                    $q->where('style_name', $style_name);
                })->when($uniq_id, function ($q) use ($uniq_id) {
                    $q->where('job_no', 'LIKE', "%{$uniq_id}%");
                })->when($style_year, function ($q) use ($style_year) {
                    $q->whereDate('created_at', Carbon::make($style_year)->format('Y-m-d'));
                })->get()->map(function ($collection) {
                    return [
                        'buyer_name' => $collection->buyer->name,
                        'buyer_id' => $collection->buyer_id,
                        'style_name' => $collection->style_name,
                        'unique_id' => $collection->job_no,
                        'style_year' => Carbon::make($collection->created_at)->format("d-m-Y"),
                        'details' => collect($collection['fabricCosting'] ? $collection['fabricCosting']['details']['details']['yarnCostForm'] : [])
                            ->map(function ($value) use ($collection) {
                                return [
                                    'unique_id' => $collection->job_no,
                                    'buyer_name' => $collection->buyer->name,
                                    'buyer_id' => $collection->buyer_id,
                                    'style_name' => $collection->style_name,
                                    'yarn_count' => $value['count'] ?? null,
                                    'yarn_color' => $value['color'] ?? null,
                                    'yarn_composition' => $value['yarn_composition'] ?? null,
                                    'percentage' => $value['percentage'] ?? null,
                                    'yarn_type' => $value['type'] ?? null,
                                    'uom' => null,
                                    'cons_qty' => $value['cons_qty'] ?? 0,
                                    'requisition_qty' => null,
                                    'rate' => $value['rate'] ?? null,
                                    'amount' => $value['amount'] ?? null,
                                    'yarn_in_house_date' => null,
                                    'remarks' => null,
                                ];
                            }),
                    ];
                })->where('details', '!=', '[]');

            return response()->json(['message' => 'Budgets Fetch Successful!', 'data' => $budgets], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::info('YarnPurchaseRequisition Budget Search: ' . $e->getMessage() . ':' . $e->getLine());

            return response()->json(['message' => 'Failed!', 'info' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function yarnRequisitionDetailsStore(Request $request): JsonResponse
    {
        try {
            foreach ($request['details'] as $detail) {
                $detail = array_merge(['requisition_id' => $request->get('yarn_requisition_id')], $detail);
                YarnPurchaseRequisitionDetails::query()->updateOrCreate(['id' => $detail['id'] ?? ''], $detail);
            }

            return response()->json([
                'requisition' => $request->all(),
                'message' => 'Successfully Updated',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'data' => null,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function yarnRequisitionDetails(Request $request): JsonResponse
    {
        $data = YarnPurchaseRequisitionDetails::query()
            ->where('requisition_id', $request->get('yarn_requisition_id'))->get()
            ->map(function ($data) {
                $fabricCosting = Budget::query()->where('job_no', $data['unique_id'])
                    ->first()->fabricCosting;
                $data['cons_qty'] = collect($fabricCosting['details']['details']['yarnCostForm'])
                        ->where("count", $data['yarn_count'])->where("color", $data['yarn_color'])
                        ->where('yarn_composition', $data['yarn_composition'])->first()['cons_qty'] ?? null;
                return $data;
            });
        return response()->json([
            'message' => 'Budgets Fetch Successful!',
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function yarnRequisitionDetailsDelete($id)
    {
        try {
            YarnPurchaseRequisition::query()->find($id)->delete();
            Session::flash('error', 'Data Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Wrong');
        }
    }
}
