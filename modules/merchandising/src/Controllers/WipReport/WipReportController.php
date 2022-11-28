<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\WipReport;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Exports\WIPReportExcel;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\WipReport\WipReport;
use SkylarkSoft\GoRMG\Merchandising\Services\WipReport\WipReportService;
use Symfony\Component\HttpFoundation\Response;

class WipReportController extends Controller
{
    public function index(Request $request)
    {
        $wipData = WipReport::with('factory:id,name')->select(['id','style','assign_factory_id','order_qty']);
        if ($request->ajax()){
            return ($wipData->addSelect(['copied_from','style','fabric_booking_details', 'trims_booking_details']))->get();
        }
        $wipData = $wipData->latest()->paginate();
        return view('merchandising::wip.index', compact('wipData'));
    }

    public function create()
    {
        return view('merchandising::wip.create_update');
    }

    public function fetchWipData(Request $request)
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $assignFactoryId = $request->get('assign_factory_id');
        $style = $request->get('style');
        $orders = Order::query()
            ->where(['factory_id' => $factoryId, 'buyer_id' => $buyerId, 'style_name' => $style])
            ->when($assignFactoryId, function ($query) use ($assignFactoryId) {
                return $query->where('assigning_factory_id', $assignFactoryId);
            })
            ->first();
        $pq = $orders ? WipReportService::formatWipData($orders) : null;

        return response()->json($pq);
    }

    public function store(Request $request)
    {
        $request->validate([
            'style' => 'unique:wip_reports'
        ]);
        try {
            $wipReport = new WipReport;
            $wipReport->fill($request->all());
            $wipReport->save();
            return response()->json($wipReport, Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(WipReport $wipReport)
    {
        try {
            $wipReport = $wipReport ? WipReportService::showWipData($wipReport) : [];
            return response()->json($wipReport);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(WipReport $wipReport, Request $request)
    {
        try {
            $wipReport->fill($request->all());
            $wipReport->save();
            return response()->json($wipReport, Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(WipReport $wipReport)
    {
        try {
            $wipReport->delete();
            Session::flash('success', 'Data Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', $exception->getMessage());
        }
        return redirect()->back();
    }

    public function view(WipReport $wipReport)
    {
        $wipReport = $wipReport ? WipReportService::showWipData($wipReport) : [];
       $fabricDetails = $wipReport['fabric_booking_details'];
       $trimsDetails = $wipReport['trims_booking_details'];

//        return $fabricDetails;
//
        return view('merchandising::wip.view', compact('wipReport','fabricDetails','trimsDetails'));
    }

    public function excel(WipReport $wipReport)
    {
        return Excel::download((new WIPReportExcel($wipReport)), 'wip-style-'.$wipReport['style'].'.xlsx');

    }
}

