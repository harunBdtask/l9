<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use Illuminate\Http\Request;
use App\Facades\MailChannelFacade;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\CommercialSetting;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContractAmendment;
use App\MailChannels\Mailers\Commercial\SalesContractAmendmentMail;

class SalesContractAmendmentController extends Controller
{
    public function index()
    {
        $amendments = SalesContractAmendment::with('factory:id,factory_name','salesContract')
            ->latest()
            ->paginate(15);

        return view('commercial::sales-contract-amendment.index', compact('amendments'));
    }

    public function createForm()
    {
        request()->validate([
            'file_no' => ['required', 'exists:sales_contracts,internal_file_no'],
        ], [
            'required' => 'Required',
            'exists' => 'Invalid File No',
        ]);

        $factories = Factory::pluck('factory_name', 'id')->prepend('Select Factory', '');
        $buyers = Buyer::pluck('name', 'id');
        $contract = SalesContract::where('internal_file_no', request('file_no'))->with(['primary_contract'])->first();
        $buying_agents = BuyingAgentModel::pluck('buying_agent_name', 'id')->prepend('Select Agent', '');

        $details = $contract->details;

        $data = compact('factories', 'buyers', 'contract', 'details', 'buying_agents');

        return view('commercial::sales-contract-amendment.create', $data);
    }

    public function store(SalesContract $contract, Request $request)
    {

        $request->validate([
            'amendment_date' => 'required',
        ]);

        $amendmentData = array_merge($contract->toArray(), [
            'contract_value' => $this->contractValue($contract, $request),
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
            "bl_clause",
            "remarks",
        ]));        
        
        $amendment = new SalesContractAmendment($amendmentData);

        try {
            DB::beginTransaction();
            $amendment->save();
            $contract
                ->details()
                ->get(['sales_contract_id', 'po_id', 'order_id', 'attach_qty', 'rate', 'attach_value'])
                ->each(function ($detail) use ($amendment) {
                    $amendment->details()->create($detail->toArray());
                });

            $amendment->updateSalesContract($request);
            DB::commit();
            
            // Send mail to Team leader
            // $settings = CommercialSetting::first();
            // if(($settings->count() > 0) && (@$settings->mailing == '1')){
            //     $userInfo = User::where('id', $settings->teamleader_id)->first();
            //     MailChannelFacade::for(new SalesContractAmendmentMail($amendmentData, $userInfo->email));
            // }

            session()->flash('alert-success', 'Successfully Saved!');

            return redirect()->to('commercial/sales-contract-amendments');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert-danger', $e->getMessage());

            return redirect()->back();
        }
    }

    public function fileNoForm()
    {
        $fileNo = SalesContract::query()->get(['id', 'internal_file_no as text']);

        return view('commercial::sales-contract-amendment.selection', compact('fileNo'));
    }

    public function form($fileNo)
    {
        $factories = Factory::pluck('factory_name', 'id')->prepend('Select Factory', '');
        $buyers = Buyer::pluck('name', 'id');

        $contract = SalesContract::where('internal_file_no', $fileNo)->firstOrFail();

        return view('commercial::sales-contract-amendment.partials.form', compact('factories', 'buyers', 'contract'))->render();
    }

    private function contractValue($contract, $request)
    {
        return $contract->contract_value + ($request->value_changed_by == 'increase' ? $request->amendment_value : -1 * $request->amendment_value);
    }

    private function adjustmentValue($contract, $request)
    {
        return $contract->claim_adjustment + ($request->claim_adjusted_by == 'increase' ? $request->claim_adjusted : -1 * $request->claim_adjusted);
    }

    public function destroy(SalesContractAmendment $contract)
    {
        try {
            DB::beginTransaction();
            $contract->details()->delete();
            $contract->delete();

            $contract->salesContract->decrement('contract_value',$contract->amendment_value);
            DB::commit();

            Session::flash('alert-success', 'Deleted Successfully!');

        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
        }

        return redirect()->back();
    }
}
