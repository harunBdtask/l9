<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisition;
use SkylarkSoft\GoRMG\Finance\Models\FundRequisitionDetail;

class FundRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $requisitions = FundRequisition::query()->orderBy('id', 'DESC')->paginate($request->get('row'));

        return view('basic-finance::fund_requisition.index', compact('requisitions'));
    }

    public function create()
    {
        return view('basic-finance::fund_requisition.form');
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $details = collect($request->input('details'))->map(function ($item) {
                $item['date'] = date('Y-m-d');

                return $item;
            });
            $requisition = new FundRequisition();
            $requisition->fill($request->all())->save();
            $requisition->details()->createMany($details);
            DB::commit();

            return response()->json(['message' => ApplicationConstant::S_CREATED, 'requisition' => $requisition]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id)
    {
        $requisition = FundRequisition::query()->findOrFail($id);

        return view('basic-finance::fund_requisition.fund_requisition_view', compact('requisition'));
    }

    public function print($id): \Illuminate\Http\Response
    {
        $requisition = FundRequisition::query()->findOrFail($id);
        $pdf = PDF::loadView(
            'basic-finance::fund_requisition.print',
            compact('requisition')
        )->setPaper('a4', 'landscape');

        return $pdf->stream($requisition->requisition_no . '_fund_requisition.pdf');
    }

    public function destroy($id)
    {
        try {
            FundRequisition::query()->findOrFail($id)->delete();
            FundRequisitionDetail::query()->where('requisition_id', $id)->delete();
            Session::flash('success', ApplicationConstant::S_DELETED);
        } catch (\Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function approve($id): RedirectResponse
    {
        $requisition = FundRequisition::find($id);
        $requisition->is_approved = 1;
        $requisition->save();

        return redirect()->back();
    }
}
