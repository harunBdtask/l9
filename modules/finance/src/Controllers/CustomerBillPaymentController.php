<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use SkylarkSoft\GoRMG\Finance\Models\CustomerBillEntry;
use SkylarkSoft\GoRMG\Finance\Services\CreateJVService;
use SkylarkSoft\GoRMG\Finance\Services\CurrencyService;
use SkylarkSoft\GoRMG\Finance\Models\CustomerBillPayment;
use SkylarkSoft\GoRMG\Finance\Models\CustomerBillPaymentInfo;
use SkylarkSoft\GoRMG\Finance\Models\CustomerBillPaymentDetail;

class CustomerBillPaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $customerBillPayments = CustomerBillPayment::query()
            ->with(['group', 'company', 'project', 'customer'])
            ->when($search, function ($query) use ($search) {
                $currencyId = collect(CurrencyService::currencies())->where('name', $search)->first()['id'] ?? '';
                $query->where('currency_id', $currencyId)
                    ->orWhereHas('group', function ($q) use ($search) {
                        $q->where('company_name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('factory_name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('project', function ($q) use ($search) {
                        $q->where('project', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->latest()
            ->paginate();
        return view('finance::customer.payment.index', compact('customerBillPayments'));
    }

    public function create()
    {
        return view('finance::customer.payment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $payInfos = $request->get('payInfos');
            $billPayment = CustomerBillPayment::query()
                ->updateOrCreate([
                    'id' => $request->get('id')
                ], $request->all());
            foreach ($request->get('details') as $detail) {
                $detail['customer_bill_payment_id'] = $billPayment->id;
                CustomerBillPaymentDetail::query()
                    ->updateOrCreate([
                        'id' => $detail['id'] ?? null
                    ], $detail);
            }
            $payInfos['customer_bill_payment_id'] = $billPayment->id;
            CustomerBillPaymentInfo::query()
                ->updateOrCreate([
                    'id' => $payInfos['id'] ?? null
                ], $payInfos);

            DB::commit();

            $billPayment = $billPayment->load('details', 'paymentInfo');

            return response()->json($billPayment, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $data = CustomerBillPayment::query()
                ->where('id', $id)
                ->with(['details', 'paymentInfo'])
                ->first();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit()
    {
        return view('finance::customer.payment.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $billPayment = CustomerBillPayment::query()->find($id);
            $billPayment->fill($request->all())->save();
            return response()->json($billPayment, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            CustomerBillPaymentDetail::query()
                ->where('customer_bill_payment_id', $id)
                ->delete();
            CustomerBillPaymentInfo::query()
                ->where('customer_bill_payment_id', $id)
                ->delete();
            CustomerBillPayment::query()->find($id)->delete();
            DB::commit();
            Session::flash('success', \S_DELETE_MSG);
        } catch (\Throwable $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG);
        }

        return redirect('/finance/customer-bill-payment');
    }

    /**
     * Fetch bill entries.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchCustomerBills(Request $request): JsonResponse
    {
        try {
            $billNumbers = $request->get('bill_nos');
            $data = CustomerBillEntry::query()
                ->with('customer')
                ->where('group_id', $request->get('group_id'))
                ->where('company_id', $request->get('company_id'))
                ->where('project_id', $request->get('project_id'))
                ->where('currency_id', $request->get('currency_id'))
                ->when($billNumbers, function ($query) use ($billNumbers) {
                    $query->whereIn('bill_no', $billNumbers);
                })
                ->get()
                ->map(function ($item) {
                    $itemDetailsCollection = collect($item->details);

                    if($item->currency_id!='1'){
                        $billAmount = $itemDetailsCollection->sum('fc_value')-$item->fc_discount??0;
                    }else{
                        $billAmount = $itemDetailsCollection->sum('total_value')-$item->discount??0;
                    }
                   
                    $prevReceived = $this->getPrevReceived($item);
                    return [
                        'bill_no' => $item->bill_no,
                        'order_no' => $itemDetailsCollection->pluck('order_no')->unique()->values()->join(', '),
                        'bill_date' => $item->bill_date,
                        // 'cons_rate' => $item->customer->conversion_rate,
                        'cons_rate' => $item->cons_rate,
                        'bill_amount' => $billAmount,
                        'prev_received' => $prevReceived,
                        'current_out_standing' => $billAmount - $prevReceived,
                        'received_amount' => null,
                        'discount' => null,
                        'due_amount' => null,
                    ];
                });

            return response()->json($data, Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getPrevReceived($item)
    {
        return CustomerBillPaymentDetail::query()
            ->where('bill_no', $item->bill_no)
            ->sum('received_amount');
    }

     //Voucher Journal Voucher POst
     public function billReceiveJVPost(Request $request, Voucher $voucher)
    {
        $this->validate($request, [
            'id' => 'required',
        ], ['required' => 'Required']);

        try {

            $voucherData = CreateJVService::customerBillReceiveJVPost($request);
      
            $voucher->fill($voucherData);    
            $voucher->save();

            return response()->json(['data' => $voucher], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
