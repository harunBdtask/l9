<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCAmendment;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;

class ExportLCAmendmentController extends Controller
{
    public function index()
    {
        $amendments = ExportLCAmendment::with(['factory:id,factory_name','ExportLc'])
            ->latest()
            ->paginate(15);

        return view('commercial::export-lc-amendment.index', compact('amendments'));
    }

    public function fileNoForm()
    {
        $fileNo = ExportLC::query()->get(['id', 'internal_file_no as text']);
        return view('commercial::export-lc-amendment.selection', compact('fileNo'));
    }

    public function createForm()
    {
        request()->validate([
            'file_no' => ['required', 'exists:export_lc,internal_file_no'],
        ], [
            'required' => 'Required',
            'exists' => 'Invalid File No',
        ]);

        $factories = Factory::pluck('factory_name', 'id')->prepend('Select Factory', '');
        $buyers = Buyer::pluck('name', 'id');
        $contract = ExportLC::where('internal_file_no', request('file_no'))->with(['primary_contract'])->first();
        $buying_agents = BuyingAgentModel::pluck('buying_agent_name', 'id')->prepend('Select Agent', '');

        $details = $contract->details;

        $data = compact('factories', 'buyers', 'contract', 'details','buying_agents');

        return view('commercial::export-lc-amendment.create', $data);
    }

    public function store(ExportLC $contract, Request $request)
    {
        $request->validate([
            'amendment_date' => 'required',
        ]);

        $amendmentData = array_merge($contract->toArray(), [
            'lc_value' => $this->contractValue($contract, $request),
            'claim_adjustment' => $this->adjustmentValue($contract, $request),
            'internal_file_no' => $contract->internal_file_no . ' (' . $request->amendment_no . ')',
        ], $request->all([
            "contract_id",
            "amendment_no",
            "amendment_date",
            "amendment_value",
            "value_changed_by",
            "last_shipment_date",
            "expiry_date",
            "shipping_mode",
            "inco_term",
            "inco_term_place",
            "port_of_entry",
            "port_of_loading",
            "port_of_discharge",
            "pay_term",
            "tenor",
            "claim_adjust",
            "claim_adjusted_by",
            "discount_clauses",
            "remarks",
        ]));

        $amendment = new ExportLCAmendment($amendmentData);

        try {
            DB::beginTransaction();
            $amendment->save();
            $contract
                ->details()
                ->get(['export_lc_id', 'po_id', 'order_id', 'attach_qty', 'rate', 'attach_value'])
                ->each(function ($detail) use ($amendment) {
                    $amendment->details()->create($detail->toArray());
                });

            $amendment->updateExportLC($request);
            DB::commit();

            session()->flash('alert-success', 'Successfully Saved!');

            return redirect()->to('commercial/export-lc-amendments');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert-danger', $e->getMessage());

            return redirect()->back();
        }
    }

    private function contractValue($contract, $request)
    {
        return $contract->lc_value + ($request->value_changed_by == 'increase' ? $request->amendment_value : -1 * $request->amendment_value);
    }

    private function adjustmentValue($contract, $request)
    {
        return $contract->claim_adjustment + ($request->claim_adjusted_by == 'increase' ? $request->claim_adjusted : -1 * $request->claim_adjusted);
    }
}
