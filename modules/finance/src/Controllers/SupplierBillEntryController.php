<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\JobNumber;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use Symfony\Component\HttpFoundation\JsonResponse;
use SkylarkSoft\GoRMG\Finance\Models\SupplierBillEntry;
use SkylarkSoft\GoRMG\Finance\Services\CreateJVService;

class SupplierBillEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = SupplierBillEntry::query()
            ->with(['group', 'company'])
            ->orderByDesc('id')
            ->search($request->get('search'))
            ->paginate();

        return view('finance::supplier.entry.index', [
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
        return view('finance::supplier.entry.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'required',
            'company_id' => 'required',
            'project_id' => 'required',
            'entry_type' => 'required',
            'supplier_id' => 'required',
            'bill_number' => 'required',
            'currency_id' => 'required',
            'con_rate' => 'required',
        ], ['required' => 'Required']);

        try {

            $billEntry = new SupplierBillEntry();
            $billEntry->fill($request->all())->save();
            $billEntry->load('supplier');
            return response()->json(['data' => $billEntry], Response::HTTP_OK);
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
        return view('finance::supplier.entry.create');
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
        $this->validate($request, [
            'group_id' => 'required',
            'company_id' => 'required',
            'project_id' => 'required',
            'entry_type' => 'required',
            'supplier_id' => 'required',
            'bill_number' => 'required',
            'currency_id' => 'required',
            'con_rate' => 'required',
        ], ['required' => 'Required']);

        try {
            $billEntry = SupplierBillEntry::find($id);
            $billEntry->fill($request->all())->save();
            $billEntry->load('supplier');
            return response()->json(['data' => $billEntry], Response::HTTP_OK);
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
        $billEntry = SupplierBillEntry::find($id);
        $billEntry->delete();

        Session::flash('success', 'Data Deleted Successfully!!');
        return redirect()->back();
    }

    public function getEditData($id): JsonResponse
    {
        try {
            $billInfo = SupplierBillEntry::query()->with('supplier')->where('id', $id)->first();
            return response()->json(['data' => $billInfo], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view(SupplierBillEntry $billEntry) {
        $billEntry->load('supplier', 'project');
        if (request('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('finance::supplier.entry.pdf',
                compact('billEntry')
            )->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('supplier_bill_entry.pdf');
        }

        return view('finance::supplier.entry.view', compact('billEntry'));
    }


    public function store_job_number(Request $request)
    {
        try {
            $jobinfo = new JobNumber();
            $jobinfo->fill($request->all())->save();
            return response()->json(['data' => $jobinfo], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        
    }
    //Voucher Journal Voucher POst
    public function createJVPost(Request $request, Voucher $voucher)
    {
        $this->validate($request, [
            'id' => 'required',
        ], ['required' => 'Required']);

        try {

            $voucherData = CreateJVService::supplierEntryJVPost($request);
            $voucher->fill($voucherData);            
            $voucher->save();

            return response()->json(['data' => $voucher], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
