<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use PDF;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\JsonResponse;
use SkylarkSoft\GoRMG\Finance\Models\CustomerBillEntry;
use SkylarkSoft\GoRMG\Finance\Services\CreateJVService;
use SkylarkSoft\GoRMG\Finance\Services\CurrencyService;
use SkylarkSoft\GoRMG\Finance\Requests\CustomerBillEntryRequest;

class CustomerBillEntryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $customerBillEntries = CustomerBillEntry::query()
            ->with(['group:id,company_name', 'company:id,factory_name', 'project:id,project', 'customer:id,name'])
            ->when($search, function ($query) use ($search) {
                $currencyId = collect(CurrencyService::currencies())->where('name', $search)->first()['id'] ?? '';
                $query->where('bill_date', $search)
                    ->orWhere('bill_no', $search)
                    ->orWhere('gin_no', $search)
                    ->orWhere('gin_date', $search)
                    ->orWhere('cons_rate', $search)
                    ->orWhere('currency_id', $currencyId)
                    ->orWhereHas('group', function ($q) use ($search) {
                        $q->where('company_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('factory_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('project', function ($q) use ($search) {
                        $q->where('project', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
            })
            ->latest()
            ->paginate();

        $currencies = CurrencyService::currencies();

        return view('finance::customer.entry.index', compact('customerBillEntries', 'currencies'));
    }

    public function create()
    {
        return view('finance::customer.entry.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerBillEntryRequest $request
     * @param CustomerBillEntry $billEntry
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CustomerBillEntryRequest $request, CustomerBillEntry $billEntry): JsonResponse
    {
        try {
            $billEntry->fill($request->all())->save();
            return response()->json($billEntry, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $billEntry = CustomerBillEntry::query()->find($id);
            return response()->json($billEntry, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit()
    {
        return view('finance::customer.entry.create');
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
            $billEntry = CustomerBillEntry::query()->find($id);
            $billEntry->fill($request->all())->save();
            return response()->json($billEntry, Response::HTTP_OK);
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
            CustomerBillEntry::query()->find($id)->delete();
            Session::flash('success', \S_DELETE_MSG);
        } catch (Exception $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG);
        }

        return redirect('/finance/customer-bill-entry');
    }

    public function view(CustomerBillEntry $billEntry)
    {
        $billEntry->load('customer');

        if (request('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('finance::customer.entry.pdf',
                compact('billEntry')
            )->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('customer_invoice.pdf');
        }

        return view('finance::customer.entry.view', compact('billEntry'));
    }

    public function getBuyerDyeingProcesses(Buyer $buyer): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($buyer->dyeing_process_info, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createInvoiceJVPost(Request $request, Voucher $voucher)
    {
        $this->validate($request, [
            'id' => 'required',
        ], ['required' => 'Required']);

        try {

            $voucherData = CreateJVService::customerCreateInvoiceJVPost($request);
            $voucher->fill($voucherData)->save();     

            return response()->json(['data' => $voucher], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
