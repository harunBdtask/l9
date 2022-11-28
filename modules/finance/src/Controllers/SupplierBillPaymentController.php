<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\Finance\Models\SupplierBillEntry;
use SkylarkSoft\GoRMG\Finance\Services\CreateJVService;
use SkylarkSoft\GoRMG\Finance\Services\CurrencyService;
use SkylarkSoft\GoRMG\Finance\Models\SupplierBillPayment;
use SkylarkSoft\GoRMG\Finance\Models\SupplierBillPaymentBillNo;


class SupplierBillPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = SupplierBillPayment::query()
            ->with(['group', 'company'])
            ->orderByDesc('id')
            ->search($request->get('search'))
            ->paginate();

        return view('finance::supplier.payment.index', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('finance::supplier.payment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $billPayment = new SupplierBillPayment();
            $billPayment->fill($request->all())->save();

            if($request->get('bill_numbers')){
                $billNos= [];
                SupplierBillPaymentBillNo::where('bill_payment_id', $billPayment->id)->delete();
                foreach($request->get('bill_numbers') as $billNo){
                    $billNos[] = [
                        'bill_payment_id' => $billPayment->id,
                        'bill_entry_id' => $billNo
                    ];
                }
                if(!empty($billNos)){
                    $bill = new SupplierBillPaymentBillNo();
                    $bill->insert($billNos);
                }
            }
            $billPayment->bill_numbers = $request->get('bill_numbers');
            
            return response()->json(['data' => $billPayment, 'bill_nos' => $request->get('bill_numbers')], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('finance::supplier.payment.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $billPayment = SupplierBillPayment::find($id);
            $billPayment->fill($request->all())->save();

            SupplierBillPaymentBillNo::where('bill_payment_id', $billPayment->id)->delete();
            if($request->get('bill_numbers')){
                $billNos= [];
                foreach($request->get('bill_numbers') as $billNo){
                    $billNos[] = [
                        'bill_payment_id' => $billPayment->id,
                        'bill_entry_id' => $billNo
                    ];
                }
                if(!empty($billNos)){
                    $bill = new SupplierBillPaymentBillNo();
                    $bill->insert($billNos);
                }
            }
            $billPayment->bill_numbers = $request->get('bill_numbers');

            return response()->json(['data' => $billPayment, 'bill_nos' => $request->get('bill_numbers')], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $billEntry = SupplierBillPayment::find($id);
        $billEntry->delete();

        Session::flash('success', 'Data Deleted Successfully!!');
        return redirect()->back();
    }

    public function fetchBillNumbers(Request $request): JsonResponse
    {
        $data['items'] = SupplierBillEntry::query()
        ->with('supplier')
        ->when($request->get('group_id'), function($q) use($request) {
            return $q->where('group_id', $request->get('group_id'));
        })
        ->when($request->get('company_id'), function($q) use($request) {
            return $q->where('company_id', $request->get('company_id'));
        })
        ->when($request->get('project_id'), function($q) use($request) {
            return $q->where('project_id', $request->get('project_id'));
        })
        ->when($request->get('supplier_id'), function($q) use($request) {
            return $q->where('supplier_id', $request->get('supplier_id'));
        })
        ->when($request->get('currency_id'), function($q) use($request) {
            return $q->where('currency_id', $request->get('currency_id'));
        })
        ->get(['bill_number as text', 'id']);

        $data['supplier'] = Supplier::find($request->get('supplier_id'));
        $data['currency_id'] = $request->currency_id??1;
        $data['currency_name'] = collect(CurrencyService::currencies())->where('id', $request->currency_id??1)->first()['name'];
        return response()->json($data);
    }
    public function fetchSupplierBill(Request $request)
    // public function fetchSupplierBill(Request $request): JsonResponse
    {
        $bill_numbers = $request->get('bill_numbers');
        $bills = SupplierBillEntry::with(['billNos'])->whereIn('id', $bill_numbers)->get();

        $billList = [];
        if($bills){
            $billList  = collect($bills)->map(function($bill){

                // Payable amount
                if($bill->currency_id!='1'){
                    $party_payable = round(($bill->party_payable/$bill->con_rate), 2);
                }else{
                    $party_payable = $bill->party_payable??0;
                }
                // Previous payment
                $previous_payment = collect($bill->billNos)->map(function($item){
                    $supplierBill = SupplierBillPayment::where('id', $item->bill_payment_id)->first();
                    return ($supplierBill->total_paid_amount + $supplierBill->total_discount);
                })->sum();

                $current_out_standing = round($party_payable - $previous_payment, 2);
                return [
                    'bill_number' => $bill->bill_number,
                    'bill_date' => $bill->bill_date,
                    'bill_receive_date' => $bill->bill_receive_date,
                    'con_rate' => $bill->con_rate,
                    'party_payable' => $party_payable,
                    'previous_payment' => $previous_payment,
                    'current_out_standing' => $current_out_standing
                ];
              });
        }
        return response()->json($billList);
    }

    public function getEditData($id): JsonResponse
    {
        try {
            $info = SupplierBillPayment::query()->with(['billNos'])->where('id', $id)->first();
            $info->bill_number = collect($info->billNos)->pluck('bill_entry_id');
            $info->currency_name = collect(CurrencyService::currencies())->where('id', $info->currency_id)->first()['name'];
            return response()->json(['data' => $info], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Voucher Journal Voucher POst
    public function billPaymentJVPost(Request $request, Voucher $voucher)
    {
        // return $request;
        $this->validate($request, [
            'id' => 'required',
        ], ['required' => 'Required']);

        try {

            $voucherData = CreateJVService::supplierBillPaymentJVPost($request);
            $voucher->fill($voucherData);            
            $voucher->save();

            return response()->json(['data' => $voucher], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
