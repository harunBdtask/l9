<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\Imports;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCAmendment;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use Symfony\Component\HttpFoundation\Response;

class B2BMarginLCAmendmentController extends Controller
{
    public $response = [];
    public $status = 200;

    public function b2bMarginLCSearch(Request $request): JsonResponse
    {
        $lc_number = $request->get('lc_number');
        $factory_id = $request->get('factory_id');
        $item_category_id = $request->get('item_category_id');
        $supplier_id = $request->get('supplier_id');

        $b2bMarginLcs = B2BMarginLC::query()
            ->with([
                'factory',
                'lienBank',
                'item',
                'supplier',
            ])
            ->when($lc_number, function ($query) use ($lc_number) {
                $query->where('lc_number', $lc_number);
            })
            ->when($factory_id, function ($query) use ($factory_id) {
                $query->where('factory_id', $factory_id);
            })
            ->when($item_category_id, function ($query) use ($item_category_id) {
                $query->where('item_id', $item_category_id);
            })
            ->when($supplier_id, function ($query) use ($supplier_id) {
                $query->where('supplier_id', $supplier_id);
            })
            ->get()
            ->map(function ($marginLc) {
                $marginLc['pi_no'] = ProformaInvoice::whereIn('id', $marginLc['pi_ids'])->pluck('pi_no')->implode(',');

                return $marginLc;
            });

        return response()->json($b2bMarginLcs);
    }

    public function store(B2BMarginLC $b2BMarginLC, Request $request): JsonResponse
    {
        $request->validate([
            'amendment_date' => 'required',
            'amendment_no' => 'required',
            'amendment_value' => 'required',
            'value_changed_by' => 'required',
        ], ['required' => 'Required']);

        try {
            $request->merge(['lc_value' => $this->calculateLCValue($b2BMarginLC, $request)]);

            $b2bAmendment = new B2BMarginLCAmendment();

            $amendmentData = array_merge(
                $b2BMarginLC->toArray(),
                $request->all($b2bAmendment->getFillable()),
                ['b_to_b_margin_lc_id' => $b2BMarginLC->id]
            );

            \DB::beginTransaction();
            $b2bAmendment->fill($amendmentData);
            $b2bAmendment->save();
            $b2bAmendment->updateB2BMarginLC();
            \DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $e) {
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $e->getMessage();
            $this->response['errorLine'] = $e->getLine();
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    private function calculateLCValue(B2BMarginLC $b2BMarginLC, Request $request)
    {
        return $b2BMarginLC->lc_value + ($request->value_changed_by == 'increase' ? $request->amendment_value : -1 * $request->amendment_value);
    }
}
