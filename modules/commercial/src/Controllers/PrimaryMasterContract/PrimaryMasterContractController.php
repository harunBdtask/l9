<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\PrimaryMasterContract;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContractDetails;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\PrimaryContractService;

class PrimaryMasterContractController extends Controller
{
    public function index()
    {
        $value = request('search');
        $contracts = PrimaryMasterContract::query()
            ->with('beneficiary', 'buyingAgent')
            ->when($value, function($query) use ($value) {
                
               return $query->where('ex_contract_number', 'LIKE', '%'.$value.'%')
                ->orWhere('contract_value', $value)
                ->orWhere('contract_date', date('Y-m-d', strtotime($value)))
                ->orWhereHas('beneficiary', function ($q) use ($value) {
                    return $q->where('factory_name', 'LIKE', '%'.$value.'%')
                        ->orWhere('factory_short_name', 'LIKE', '%'.$value.'%');
                })
                ->orWhereHas('buyingAgent', function ($q) use ($value) {
                    return $q->where('buying_agent_name', 'LIKE', '%'.$value.'%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate();

        return view('commercial::primary-master-contract.index', compact('contracts'));
    }

    public function create()
    {
        return view('commercial::primary-master-contract.create_update');
    }

    public function show(PrimaryMasterContract $primaryMasterContract)
    {
        return response()->json($primaryMasterContract->load('details'));
    }

    public function store(Request $request, PrimaryMasterContract $primaryMasterContract)
    {
        try {
            $primaryMasterContract->fill($request->all())->save();
            return response()->json(['data' => $primaryMasterContract, 'message' => 'Save Successfully!'], ResponseAlias::HTTP_ACCEPTED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function update(Request $request, PrimaryMasterContract $primaryMasterContract)
    {
        try {
            $primaryMasterContract->fill($request->all())->update();
            return response()->json(['data' => $primaryMasterContract, 'message' => 'Update Successfully!'], ResponseAlias::HTTP_ACCEPTED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function storeDetails(Request $request)
    {
        try {
            $primaryMasterContractDetails = PrimaryMasterContractDetails::firstOrNew(['id' => $request->id ?: null]);
            $primaryMasterContractDetails->fill($request->all())->save();
            return response()->json(['data' => $primaryMasterContractDetails, 'message' => $request->id ? 'Updated Successfully!' : 'Saved Successfully!'], ResponseAlias::HTTP_ACCEPTED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function deleteDetails(PrimaryMasterContractDetails $primaryMasterContractDetails)
    {
        try {
            $primaryMasterContractDetails->delete();
            return response()->json(['data' => $primaryMasterContractDetails, 'message' => 'Deleted Successfully!'], ResponseAlias::HTTP_ACCEPTED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function destroy(PrimaryMasterContract $primaryMasterContract, Request $request)
    {

        try {
            $primaryMasterContract->details()->delete();
            $primaryMasterContract->delete();
            if ($request->ajax()) {
                return response()->json(['data' => $primaryMasterContract, 'message' => 'Deleted Successfully!'], ResponseAlias::HTTP_ACCEPTED);
            }
            Session::flash('success', 'Data Deleted Successfully!!');
            return redirect()->back();
        } catch (\Exception $exception) {
            if ($request->ajax()) {
                return response()->json($exception->getMessage());
            }
            Session::flash('error', 'Something went wrong!');

        }
    }

    public function dependentData()
    {
        $data['inco_terms'] = collect(PrimaryMasterContract::INCO_TERMS)->map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        })->values();

        $data['shipping_modes'] = collect(PrimaryMasterContract::SHIPPING_MODES)->map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        })->values();

        $data['contract_sources'] = collect(PrimaryMasterContract::CONTRACT_SOURCES)->map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        })->values();

        $data['pay_terms'] = collect(PrimaryMasterContract::PAY_TERMS)->map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        })->values();

        $data['export_item_categories'] = collect(PrimaryMasterContract::EXPORT_ITEM_CATEGORIES)->map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        })->values();

        $data['lien_banks'] = collect(LienBank::pluck('name','id'))->map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        })->values();

        return response()->json($data);
    }

    public function view($id)
    {
         $contracts = PrimaryMasterContract::query()
                    ->with([
                        'details',
                        'buyingAgent',
                        'beneficiary',
                        'lienBank'
                    ])
                    ->findOrFail($id);
        $result = (new PrimaryContractService())->formatPrimaryContract($contracts);
        return view('commercial::primary-master-contract.view',[
            'contracts' => $contracts, 'dataItem' => $result
        ]);
    }

    public function getPdf($id)
    {
        $contracts = PrimaryMasterContract::query()
                    ->with([
                        'details',
                        'buyingAgent',
                        'beneficiary',
                        'lienBank'
                    ])
                    ->findOrFail($id);
        $result = (new PrimaryContractService())->formatPrimaryContract($contracts);
        $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('commercial::primary-master-contract.pdf', [
                    'contracts' => $contracts, 'dataItem' => $result
                ])
                ->setPaper('a4')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer')
                ]);
            return $pdf->stream('export-contract.pdf');
    }

    public function agentPrimaryContracts($id)
    {

        $items = PrimaryMasterContract::where('buying_agent_id', $id)->get();
        $result = collect($items)->map(function($item){
            return '<option value="'.$item->id.'">'.$item->unique_id.'</option>';
        });
        return $result->prepend('<option value="">Select Contract</option>');

    }

    // Primary Contracts
    public function getPrimaryContract($id)
    {
        $item = PrimaryMasterContract::where('id', $id)->first();
        return $item;

    }
}
