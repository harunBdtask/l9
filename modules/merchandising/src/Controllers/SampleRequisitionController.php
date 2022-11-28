<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\QuotationInquiry;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisitionDetail;
use SkylarkSoft\GoRMG\Merchandising\Requests\SampleRequisitionRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\SampleRequisition\SampleRequisitionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SampleRequisitionController extends Controller
{
    public function index()
    {
        return view('merchandising::sample-requisitions.index');
    }

    public function list(): JsonResponse
    {
        $q = request('q');
        $samples = SampleRequisition::with('factory:id,factory_name', 'buyer:id,name', 'department:id,product_department', 'merchant')
            ->when($q, function ($query) use ($q) {
                $key = '%' . $q . '%';
                $query->where('requisition_id', 'LIKE', $key)
                    ->orWhere('style_name', 'LIKE', $key)
                    ->orWhereHas('buyer', function ($query) use ($key) {
                        $query->where('name', 'LIKE', $key);
                    })->orWhereHas('department', function ($query) use ($key) {
                        $query->where('product_department', 'LIKE', $key);
                    })->orWhereHas('merchant', function ($query) use ($key) {
                        $query->where('first_name', 'LIKE', $key)->orWhere('last_name', 'LIKE', $key);
                    });
            })
            ->latest()
            ->paginate();

        return response()->json($samples);
    }

    public function stylesSearch(): JsonResponse
    {
        $factoryId = request('factory_id');
        $buyerId = request('buyer_id');
        $year = request('year');
        $sampleStage = request('sample_stage');
        $inquiryId = request('inquiry_id');
        $styleName = request('style_name');
        $uniqId = request('uniq_id');
        $startDate = request('start_date');
        $endDate = request('end_date');

        if ($sampleStage == SampleRequisition::AFTER_ORDER) {
            $orders = Order::with('factory', 'buyer')
                ->when($factoryId, function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                })
                ->when($buyerId, function ($query) use ($buyerId) {
                    $query->where('buyer_id', $buyerId);
                })
                ->when($year, function ($query) use ($year) {
                    $query->whereYear('created_at', $year);
                })
                ->when($uniqId, function ($query) use ($uniqId) {
                    $query->where('job_no', $uniqId);
                })
                ->when($styleName, function ($query) use ($styleName) {
                    $query->where('style_name', $styleName);
                })->get();

            return response()->json(['data' => $orders]);
        }

        if (in_array($sampleStage, [SampleRequisition::BEFORE_ORDER, SampleRequisition::RND])) {
            $quotationInquiries = QuotationInquiry::with('factory', 'buyer')
                ->when($factoryId, function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                })
                ->when($buyerId, function ($query) use ($buyerId) {
                    $query->where('buyer_id', $buyerId);
                })
                ->when($year, function ($query) use ($year) {
                    $query->whereYear('created_at', $year);
                })
                ->when($styleName, function ($query) use ($styleName) {
                    $query->where('style_name', $styleName);
                })
                ->when($inquiryId, function ($query) use ($inquiryId) {
                    $query->where('quotation_id', $inquiryId);
                })
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('inquiry_date', [$startDate, $endDate]);
                })->get();

            return response()->json(['data' => $quotationInquiries]);
        }

        return \response()->json(\request()->all());
    }

    public function store(SampleRequisitionRequest $request): JsonResponse
    {
        try {
            $requisition = new SampleRequisition($request->all());
            $requisition->save();

            return response()->json(['message' => 'Successfully Saved!', 'sample' => $requisition], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(SampleRequisition $requisition, SampleRequisitionRequest $request): JsonResponse
    {
        try {
            $requisition->update($request->except('requisition_id'));

            return response()->json(['message' => 'Successfully Updated!', 'sample' => $requisition]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(SampleRequisition $requisition): JsonResponse
    {
        $buyers = Buyer::where('factory_id', $requisition->factory_id)->get(['id', 'name as text']);
        $seasons = Season::where('buyer_id', $requisition->buyer_id)->get(['id', 'season_name as text']);
        $merchants = User::where('factory_id', $requisition->factory_id)->where('email', '<>', 'super@skylarksoft.com')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->first_name . ' ' . $user->last_name,
            ];
        });

        return \response()->json(compact('requisition', 'buyers', 'seasons', 'merchants'));
    }

    public function currencies(): JsonResponse
    {
        $currencies = Currency::all(['id', 'currency_name as text']);

        return response()->json($currencies);
    }

    public function buyingAgents(): JsonResponse
    {
        $agents = BuyingAgentModel::query()
            ->with('buyingAgentWiseFactories')
            ->withoutGlobalScopes()
            ->filterWithAssociateFactory('buyingAgentWiseFactories', factoryId())
            ->get(['id', 'buying_agent_name as text']);

        return response()->json($agents);
    }

    public function dealingMerchants(Factory $factory): JsonResponse
    {
        $merchants = $factory->users()->where('email', '<>', 'super@skylarksoft.com')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->first_name . ' ' . $user->last_name,
            ];
        });

        return response()->json($merchants);
    }

    public function qtyFormData(): JsonResponse
    {
        $styleName = request('style_name');
        $samples = GarmentsSample::where('status', 'active')->get(['id', 'name as text']);
        $colors = Color::where('status', 1)->get(['id', 'name as text']);

        if ($styleName) {
            $items = (new SampleRequisitionService())->garmentItemsByStyleName($styleName);
            return response()->json(compact('items', 'samples', 'colors'));
        }

        $items = GarmentsItem::all(['id', 'name as text']);

        return response()->json(compact('items', 'samples', 'colors'));
    }

    public function uploadFile(Request $request): JsonResponse
    {
        try {
            $filePath = null;
            $destination = $request->input('destination') ?: '';

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = Storage::put($destination, $file);
            }

            if ($request->has('prevFile') && $prevFilePath = $request->input('prevFile')) {
                Storage::delete($prevFilePath);
            }

            return response()->json(['status' => 'success', 'file' => $filePath]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getFabricDetails(Request $request): JsonResponse
    {
        $request->validate([
            'style_name' => 'required',
            'item_id' => 'required'
        ]);

        $budget = Budget::where('style_name', $request->style_name)
            ->select('id')
            ->first();

        $budgetCostingDetails = BudgetCostingDetails::whereType(BudgetCostingDetails::FABRIC_COSTING)
            ->whereBudgetId($budget->id)
            ->first();

        $fabricDetails = Arr::get($budgetCostingDetails ? $budgetCostingDetails->toArray() : [], 'details.details.fabricForm');

        $itemDetails = collect($fabricDetails)
            ->where('garment_item_id', $request->item_id)
            ->first();

        $itemDetails = collect($itemDetails)
            ->except(['greyConsForm', 'contrastForm', 'stripMeasurement']);

        return response()->json($itemDetails);
    }

    public function requiredSamples(Request $request): JsonResponse
    {
        $request->validate(['requisition_id' => 'required']);

        $samplesId = SampleRequisitionDetail::where('sample_requisition_id', $request->requisition_id)
            ->pluck('sample_id');

        $samples = GarmentsSample::whereIn('id', $samplesId)->get(['id', 'name as text']);

        return response()->json($samples);
    }

    public function delete(SampleRequisition $sampleRequisition): JsonResponse
    {
        try {
            DB::beginTransaction();
            $sampleRequisition->details()->delete();
            $sampleRequisition->fabrics()->delete();
            $sampleRequisition->accessories()->delete();
            $sampleRequisition->delete();
            DB::commit();

            return response()->json('Delete Success', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
