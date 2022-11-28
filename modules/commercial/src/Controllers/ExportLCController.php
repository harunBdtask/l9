<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Commercial\Models\CommercialVariable;
use SkylarkSoft\GoRMG\Commercial\Rules\ExportLCAttachQtyRule;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\Commercial\Rules\ExportLCAttachValueRule;

class ExportLCController extends Controller
{
    public function index()
    {
        $contracts = ExportLC::with(['factory:id,factory_name','sales_contract:id,internal_file_no'])->latest()->paginate(15);
        $contracts->getCollection()->transform(function($item, $key){
            $item->buyer = Buyer::whereIn('id', (!is_array($item->buyer_id)?explode(" ", $item->buyer_id):$item->buyer_id))->get();
            $item->buyers = collect($item->buyer)->implode('name', ',');
            return $item;
        });

        return view('commercial::export-lc.index', compact('contracts'));
    }

    public function search()
    {
        $value = request('search');
        $contracts = ExportLC::where('internal_file_no', 'LIKE', $value)
            ->orWhereHas('factory', function ($query) use ($value) {
                return $query->where('factory_name', 'LIKE', $value);
            })
            ->orWhereHas('buyer', function ($query) use ($value) {
                return $query->where('name', 'LIKE', $value);
            })
            ->with('buyer:id,name', 'factory:id,factory_name')->latest()->paginate(15);

        return view('commercial::export-lc.index', compact('contracts', 'value'));
    }

    public function create()
    {
        $factories = Factory::pluck('factory_name', 'id');
        $buyers = Buyer::pluck('name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $lien_banks = LienBank::pluck('name', 'id');
        $buying_agents = BuyingAgentModel::pluck('buying_agent_name', 'id');
        $sales_contracts = SalesContract::pluck('internal_file_no', 'id')->prepend('Select','');
        $contract = $primary_contracts = null;
        $btb_limit_percent = array_key_first($factories->toArray()) ? CommercialVariable::where(['variable_name' => 'btb_limit_percent' ,'factory_id' => factoryId()])->first()['value'] ?? 0 : 0;
        return view('commercial::export-lc.create', compact('contract', 'factories', 'buyers', 'currencies', 'lien_banks','btb_limit_percent','buying_agents','primary_contracts','sales_contracts'));
    }

    public function store(Request $request)
    {
       $this->validateRequest($request);
        // $this->validateClaimAdjustment($request);

        try {
            $contract = new ExportLC($request->all());
            $contract->save();
            Session::flash('alert-success', 'Successfully Saved!');
            $url = route('export.contract.edit', ['contract' => $contract->getKey()]);
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
            $request->flash();

            return response($e->getMessage());
        }

        return redirect($url);
    }

    private function validateRequest($request)
    {
        $request->validate([
            'beneficiary_id' => 'required',
            'internal_file_no' => 'required',
            'lc_number' => 'required',
            'lc_date' => 'required',
            'export_item_category' => 'required',
            'last_shipment_date' => 'required',
            'year' => [Rule::in(range(2020, 2050))],
            'claim_adjustment' => 'nullable|numeric'
        //    'bank_file_no' => 'required'
        ]);
    }

    private function validateClaimAdjustment($request)
    {
        $request->validate([
            'claim_adjustment' => 'nullable|numeric',
        ]);
    }

    public function edit(ExportLC $contract)
    {
        $contract->loadCount('amendments');
        $factories = Factory::pluck('factory_name', 'id')->prepend('Select Beneficiary', '');
        $buyers = Buyer::pluck('name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $lien_banks = LienBank::pluck('name', 'id');
        $btb_limit_percent =  $contract->btb_limit_percent ?? 0;
        $buying_agents = BuyingAgentModel::pluck('buying_agent_name', 'id');
        $sales_contracts = SalesContract::pluck('internal_file_no', 'id')->prepend('Select','');
        $primary_contracts = $contract->primary_contract()->get();

        return view('commercial::export-lc.create', compact('factories', 'buyers', 'contract', 'currencies', 'lien_banks', 'btb_limit_percent','buying_agents','primary_contracts','sales_contracts'));
    }

    public function update(ExportLC $contract, Request $request)
    {
       $this->validateRequest($request);
        // $this->validateClaimAdjustment($request);

        try {
            $contract->fill($request->except(['buyer_id']));
            $contract->save();
            Session::flash('alert-success', 'Successfully Updated!');
            $url = route('export.contract.edit', ['contract' => $contract->getKey()]);
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
            $request->flash();

            return redirect()->back();
        }

        return redirect($url);
    }

    public function destroy(ExportLC $contract)
    {
        try {
            if (!$contract->amendments()->count()) {
                $contract->details()->delete();
                $contract->delete();
                Session::flash('alert-success', 'Deleted Successfully!');
            } else {
                Session::flash('alert-danger', 'Already amended! You can not delete!');
            }
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
        }

        return redirect()->back();
    }

    public function exportContractDetailsSelection($type, $id)
    {
        $view = '';

        if ($type == 'style') {
            $purchaseOrders = PurchaseOrder::with('order')
                ->where('order_id', $id)
                ->get();
        }

        if ($type == 'po') {
            $purchaseOrders = PurchaseOrder::with('order')
                ->where('id', $id)
                ->get();
        }

        $view = view('commercial::export-lc.partials.details-selection', compact('purchaseOrders'))->render();

        return response($view);
    }

    public function generateDetailForm()
    {
        $purchaseOrders = PurchaseOrder::with('order.productCategory', 'poDetails.garmentItem', 'exportLCs')->find(json_decode(request('ids')));
        $export_lc_id = request()->get('export_lc_id');

        $view = view('commercial::export-lc.partials.detail-form', compact('purchaseOrders', 'export_lc_id'))
            ->render();

        return response($view);
    }

    public function fetchDetails(ExportLC $contract)
    {
        $details = $contract->details;

        return view('commercial::export-lc.partials.details-list', compact('details'));
    }

    public function storeDetails(ExportLC $contract, Request $request)
    {
        $request->validate([
//            'attach_qty.*' => ['required', new ExportLCAttachQtyRule()],
            'rate.*' => 'required',
            'attach_value.*' => ['required', new ExportLCAttachValueRule($contract)],
        ], [
            'required' => 'Required',
        ]);

        $request->validate([
            'attach_qty.*' => 'required',
            'rate.*' => 'required',
            'attach_value.*' => 'required',
        ], [
            'required' => 'Required',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->input('po_id') as $idx => $poId) {
                $detail = $contract->details()->where('po_id', $poId)->first();

                if ($detail) {
                    $detail->attach_qty = $request->input('attach_qty.' . $idx);
                    $detail->rate = $request->input('rate.' . $idx);
                    $detail->attach_value = $request->input('attach_value.' . $idx);
                    $detail->save();

                    continue;
                }

                $contract
                    ->details()
                    ->create([
                        'po_id' => $poId,
                        'order_id' => $request->input('order_id.' . $idx),
                        'attach_qty' => $request->input('attach_qty.' . $idx),
                        'rate' => $request->input('rate.' . $idx),
                        'attach_value' => $request->input('attach_value.' . $idx),
                    ]);
            }
            DB::commit();

            return response(['status' => 'success']);
        } catch (\Exception $e) {
            return response(['status' => 'danger']);
        }
    }

    public function deleteDetail(ExportLCDetail $detail)
    {
        try {
            $detail->delete();
        } catch (\Exception $e) {
        }

        return response(['message' => 'ok']);
    }

    public function view(ExportLC $contract)
    {
        $contract->buyer = Buyer::whereIn('id', (!is_array($contract->buyer_id)?explode(" ", $contract->buyer_id):$contract->buyer_id))->get();
        $contract->buyers = collect($contract->buyer)->implode('name', ',');

        return view('commercial::export-lc.view.view', compact('contract'));
    }

    public function pdf(ExportLC $contract)
    {
        $contract->buyer = Buyer::whereIn('id', (!is_array($contract->buyer_id)?explode(" ", $contract->buyer_id):$contract->buyer_id))->get();
        $contract->buyers = collect($contract->buyer)->implode('name', ',');

        
        $pdf = PDF::loadView('commercial::export-lc.view.pdf', compact('contract'));
        return $pdf->stream('export_lc');
    }
}
