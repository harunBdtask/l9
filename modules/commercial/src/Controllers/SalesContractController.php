<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Commercial\Rules\AttachQtyRule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Commercial\Rules\AttachValueRule;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCDetail;
use SkylarkSoft\GoRMG\Commercial\Models\CommercialVariable;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContractDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\NotificationService;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;


class SalesContractController extends Controller
{
    public function index()
    {
         $contracts = SalesContract::with(['factory:id,factory_name','lienBank'])->latest()->paginate(15);

        $contracts->getCollection()->transform(function($item, $key)
        {
            $item->buyer = Buyer::whereIn('id', (!is_array($item->buyer_id)?explode(" ", $item->buyer_id):$item->buyer_id))->get();
            $item->buyers = collect($item->buyer)->implode('name', ',');
            return $item;
        });
        return view('commercial::sales-contract.index', compact('contracts'));
    }

    public function search()
    {
        $value = request('search');

        $contracts = SalesContract::query()
            ->with('factory:id,factory_name','lienBank')
            ->when($value, function($query) use ($value){

                return $query->where('internal_file_no', 'LIKE', '%'.$value.'%')
                ->orWhere('contract_number', 'LIKE', '%'.$value.'%')
                ->orWhere('year', $value)
                ->orWhereDate('contract_date', $value)
                ->orWhereDate('last_shipment_date', $value)
                ->orWhereHas('factory', function ($query) use ($value) {
                    return $query->where('factory_name', 'LIKE', $value);
                })
                ->orWhereHas('buyer', function ($query) use ($value) {
                    return $query->where('name', 'LIKE', $value);
                });
            })
            ->latest()->paginate(15);

        $contracts->getCollection()->transform(function($item, $key)
        {
            $item->buyer = Buyer::whereIn('id', (!is_array($item->buyer_id)?explode(" ", $item->buyer_id):$item->buyer_id))->get();
            $item->buyers = collect($item->buyer)->implode('name', ',');
            return $item;
        });

        return view('commercial::sales-contract.index', compact('contracts', 'value'));
    }

    public function create()
    {
        $factories = Factory::query()->pluck('factory_name', 'id');
        $buyers = Buyer::query()->pluck('name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $lien_banks = LienBank::pluck('name', 'id');
        $buying_agents = BuyingAgentModel::query()->pluck('buying_agent_name', 'id');
        $contract = null;
        $btb_limit_percent = array_key_first($factories->toArray()) ? CommercialVariable::where(['variable_name' => 'btb_limit_percent' ,'factory_id' => factoryId()])->first()['value'] : 0;

        return view('commercial::sales-contract.create', compact('factories', 'buyers', 'contract', 'currencies', 'lien_banks', 'btb_limit_percent','buying_agents'));
    }

    // public function ordersForSalesContract(Buyer $buyer, Request $request): JsonResponse
    public function ordersForSalesContract($ids, Request $request): JsonResponse
    {
        $term = $request->get('search') ?? null;
        $buyer_ids = array_map('intval', explode(',', $ids));

        // $orders = $buyer->orders()->when($term, function ($q) use ($term) {
        $orders = Order::whereIn('buyer_id',$buyer_ids)->when($term, function ($q) use ($term) {
            return $q
                ->where('job_no', 'LIKE', '%' . $term . '%')
                ->orWhere('style_name', 'LIKE', '%' . $term . '%');
        })->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'text' => $order->job_no . ' ' . $order->style_name,
                'buyer_id' => $order->buyer_id
            ];
        });

        return response()->json($orders);
    }

    public function purchaseOrdersForOrder(Order $order, Request $request): JsonResponse
    {
        $term = $request->get('search') ?? null;

        $purchaseOrders = $order->purchaseOrders()->when($term, function ($query) use ($term) {
            return $query->where('po_no', 'LIKE', '%' . $term . '%');
        })->get([
            'id',
            'po_no as text',
        ]);

        return response()->json($purchaseOrders);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        try {
            $contract = new SalesContract($request->all());
            $contract->save();
            Session::flash('alert-success', 'Successfully Saved!');
            $url = route('sales.contract.edit', ['contract' => $contract->getKey()]);

            // Notify to  commercial team leader
            NotificationService::saleContractNotification($contract->id); 
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
            $request->flash();

            return redirect()->back();
        }

        return redirect($url);
    }

    public function update(SalesContract $contract, Request $request)
    {
        $this->validateRequest($request);

        try {
            // $contract->fill($request->except(['buyer_id']));
            $contract->fill($request->all());
            $contract->save();
            Session::flash('alert-success', 'Successfully Saved!');
            $url = route('sales.contract.edit', ['contract' => $contract->getKey()]);
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
            $request->flash();

            return redirect()->back();
        }

        return redirect($url);
    }

    public function salesContractDetailsSelection($type, $id)
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

        $view = view('commercial::sales-contract.partials.details-selection', compact('purchaseOrders'))->render();

        return response($view);
    }

    public function edit(SalesContract $contract)
    {
        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select Factory', '');
        $buyers = Buyer::query()->pluck('name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $lien_banks = LienBank::pluck('name', 'id');
        $buying_agents = BuyingAgentModel::query()->pluck('buying_agent_name', 'id');


        $details = $contract->details()->get();
        $contract->loadCount('amendments');
        $btb_limit_percent =  $contract->btb_limit_percent ?? 0;

        $primary_contracts = PrimaryMasterContract::find($contract->primary_contract_id);

        return view('commercial::sales-contract.create', compact('factories', 'buyers', 'contract', 'details', 'currencies', 'lien_banks', 'btb_limit_percent','buying_agents','primary_contracts'));
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'beneficiary_id' => 'required',
            'internal_file_no' => 'required',
            'contract_number' => 'required',
            'contract_value' => 'required',
            'contract_date' => 'required',
            'last_shipment_date' => 'required',
            'export_item_category' => 'required',
            'year' => 'required',
            'claim_adjustment' => 'nullable|numeric',
            'hs_code' => 'required'
        ]);
    }

    public function generateDetailForm()
    {
        $purchaseOrders = PurchaseOrder::with('order.productCategory','salesContracts')->find(json_decode(request('ids')));
        $sales_contract_id = request()->get('sales_contract_id');

        $view = view('commercial::sales-contract.partials.detail-form', compact('purchaseOrders', 'sales_contract_id'))
            ->render();
        return response($view);
    }

    public function storeDetails(SalesContract $contract, Request $request)
    {
        $request->validate([
            //'attach_qty.*' => ['required', new AttachQtyRule()],
            'rate.*' => 'required',
            'attach_value.*' => ['required', new AttachValueRule($contract)],
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

    public function fetchDetails(SalesContract $contract)
    {
        $details = $contract->details;

        return view('commercial::sales-contract.partials.details-list', compact('details'));
    }

    public function deleteDetail(SalesContractDetail $detail)
    {
        try {
            $detail->delete();
        } catch (\Exception $e) {
        }

        return response(['message' => 'ok']);
    }

    public function destroy(SalesContract $contract): \Illuminate\Http\RedirectResponse
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

    public function view(SalesContract $contract)
    {

        $contract->buyer = Buyer::whereIn('id', (!is_array($contract->buyer_id)?explode(" ", $contract->buyer_id):$contract->buyer_id))->get();
        $contract->buyers = collect($contract->buyer)->implode('name', ',');

        return view('commercial::sales-contract.view.view', compact('contract'));
    }

    public function pdf(SalesContract $contract)    
    {
        $contract->buyer = Buyer::whereIn('id', (!is_array($contract->buyer_id)?explode(" ", $contract->buyer_id):$contract->buyer_id))->get();
        $contract->buyers = collect($contract->buyer)->implode('name', ',');
        
        $pdf = PDF::loadView('commercial::sales-contract.view.pdf', compact('contract'));
        return $pdf->stream('sales-contract');
    }   

    public function buyerIdToJson()
    {

         SalesContract::all()->map(function($item){
            $sc = SalesContract::find($item->id);
            $sc->buyer_id = (!is_array($item->buyer_id)?[json_encode($item->buyer_id)]:$item->buyer_id);
            $sc->update();
        });

        ExportLC::all()->map(function($item){

            $elc = ExportLC::find($item->id);
            $elc->buyer_id = (!is_array($item->buyer_id)?[json_encode($item->buyer_id)]:$item->buyer_id);
            $elc->update();
        });
        
        B2BMarginLCDetail::all()->map(function($item){

            $elc = B2BMarginLCDetail::find($item->id);
            $elc->buyer_id = (!is_array($item->buyer_id)?[json_encode($item->buyer_id)]:$item->buyer_id);
            $elc->update();
        });
    }
}
