<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\PrimaryMasterContractAmendment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContractAmendment\PrimaryMasterContractAmendment;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PrimaryMasterContractAmendmentController extends Controller
{
    public function index()
    {
        $contracts = PrimaryMasterContractAmendment::query()->with('beneficiary', 'buyingAgent')->orderBy('id', 'desc')->paginate();
        return view('commercial::primary-master-contract-amendment.index', compact('contracts'));
    }

    public function create()
    {
        return view('commercial::primary-master-contract-amendment.create_update');

    }

    public function fetchContractNo(Request $request)
    {
        $term = $request->get('search');
        $contractNos = PrimaryMasterContract::query()
            ->when($term, function ($query) use ($term) {
                return $query->where('unique_id', 'like', '%' . $term . '%');
            })
            ->get()->map(function ($item) {
                return [
                    'id' => $item->unique_id,
                    'text' => $item->unique_id
                ];
            })->values();
        return response()->json([
            'data' => $contractNos
        ]);
    }

    public function getMasterContractData($uniqueId)
    {
        $contract = PrimaryMasterContractAmendment::with('details')->where('unique_id', $uniqueId)->orderBy('id','desc')->first();
        if (!$contract){
            $contract = PrimaryMasterContract::with('details')->where('unique_id', $uniqueId)->first();
        }
        return response()->json($contract);
    }

    public function store(Request $request, PrimaryMasterContractAmendment $primaryMasterContractAmendment)
    {
        $this->validate($request, [
            'amend_date' => 'required'
        ]);
        try {
            $primaryMasterContractAmendment->fill($request->all())->save();
            $primaryMasterContractAmendment->details()->createMany($request->get('details'));
            return response()->json(['data' => $primaryMasterContractAmendment->load('details'), 'message' => 'Save Successfully!'], ResponseAlias::HTTP_ACCEPTED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
