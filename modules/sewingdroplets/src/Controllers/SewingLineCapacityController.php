<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use DB, Session, Exception;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingLineCapacity;

class SewingLineCapacityController extends Controller
{

    public function lineCapacityEntry()
    {
        $floors = Floor::pluck('floor_no', 'id');
        return view('sewingdroplets::forms.sewing_line_capacity_entry', [
            'floors' => $floors,
        ]);
    }

    public function getLineCapacityEntryForm($floor_id)
    {
        $sewingLineCapacity = null;
        $lines = Line::where('floor_id', $floor_id)
            ->orderBy('sort', 'asc')
            ->get();
        if ($floor_id) {
            $sewingLineCapacity = SewingLineCapacity::withoutGlobalScope('factoryId')
                ->join('lines', 'sewing_line_capacities.line_id', 'lines.id')
                ->where('sewing_line_capacities.floor_id', $floor_id)
                ->where('sewing_line_capacities.factory_id', factoryId())
                ->orderBy('lines.sort', 'asc')
                ->orderBy('sewing_line_capacities.id', 'asc')
                ->select(['sewing_line_capacities.*', 'lines.*'])
                ->get();
        }

        $view = view('sewingdroplets::forms.line_capacity_entry_form', compact('sewingLineCapacity', 'lines'))->render();
        return response()->json(['view' => $view]);
    }

    public function lineCapacityEntryPost(Request $request)
    {
        $lineWiseCapacityInput = [];
        if (!$request->has('line_id')) {
            Session::flash('error', 'No Line is given!!');
            return redirect()->back();
        }
        $no_of_rows = count($request->line_id);

        if ($no_of_rows > 0) {
            $dateTime = Carbon::now();
            for ($i = 0; $i < $no_of_rows; $i++) {
                $lineWiseCapacityInput[] = [
                    'floor_id' => $request->floor_id,
                    'line_id' => $request->line_id[$i],
                    'operator' => $request->operator[$i] ?? 0,
                    'helper' => $request->helper[$i] ?? 0,
                    'absent_percent' => $request->absent_percent[$i] ?? 0,
                    'working_hour' => $request->working_hour[$i] ?? 0,
                    'working_minutes' => $request->working_hour[$i] ? $request->working_hour[$i] * 60 : 0,
                    'line_efficiency' => $request->line_efficiency[$i] ?? 0,
                    'capacity_available_minutes' => $request->capacity_available_minutes[$i] ?? 0,
                    'factory_id' => factoryId(),
                    'created_at' => $dateTime,
                    'updated_at' => $dateTime
                ];
            }
        }

        try {
            DB::transaction(function () use ($lineWiseCapacityInput, $request) {
                // delete previous plan data
                SewingLineCapacity::where([
                    'floor_id' => $request->floor_id,
                ])->forceDelete();
                // insert new data
                SewingLineCapacity::insert($lineWiseCapacityInput);
                Session::flash('success', S_UPDATE_MSG);
            });

        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function orderWiseCapacityInquiry()
    {
        $buyers = Buyer::orderBy('name', 'asc')->pluck('name', 'id');
        $floors = Floor::pluck('floor_no', 'id');

        return view('sewingdroplets::pages.order_wise_capacity_inquiry', [
            'buyers' => $buyers,
            'floors' => $floors,
        ]);
    }

    public function getPoCapacitySection(Request $request)
    {
        $purchase_order_id = $request->purchase_order_id;
        $smv = $request->smv ?? 0;
        $purchase_order = PurchaseOrder::findOrFail($purchase_order_id);

        $html = view('sewingdroplets::pages.get_po_capacity_section', [
            'purchase_order' => $purchase_order,
            'smv' => $smv,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function getFloorCapacitySection(Request $request)
    {
        $purchase_order_id = $request->purchase_order_id;
        $smv = $request->smv ?? 0;
        $purchase_order = PurchaseOrder::findOrFail($purchase_order_id);
        $sewing_line_capacity = SewingLineCapacity::orderBy('floor_id','desc')->get();

        $html = view('sewingdroplets::pages.get_floor_line_capacity_section', [
            'purchase_order' => $purchase_order,
            'sewing_line_capacity' => $sewing_line_capacity,
            'smv' => $smv,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function getLineCapacitySection(Request $request)
    {
        $floor_id = $request->floor_id ?? null;
        $purchase_order_id = $request->purchase_order_id ?? null;
        $purchase_order = PurchaseOrder::findOrFail($purchase_order_id);
        $smv = $request->smv ?? 0;
        $sewing_line_capacity = SewingLineCapacity::where('floor_id', $floor_id)->get();

        $html = view('sewingdroplets::pages.get_floor_line_capacity_section', [
            'purchase_order' => $purchase_order,
            'sewing_line_capacity' => $sewing_line_capacity,
            'smv' => $smv,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function getLineCapacityInformation($line_id, $smv)
    {
        $sewing_line_capacity = SewingLineCapacity::where('line_id', $line_id)->first();
        $manpower = $sewing_line_capacity->operator + $sewing_line_capacity->helper;
        $working_hour = $sewing_line_capacity->working_hour;
        $line_capacity = ($smv && $smv > 0) ? round($sewing_line_capacity->capacity_available_minutes / $smv) : 0;
        $line_efficiency = $sewing_line_capacity->line_efficiency. ' %';


        return response()->json([
            'manpower' => $manpower,
            'working_hour' => $working_hour,
            'line_capacity' => $line_capacity,
            'line_capacity_min' => $sewing_line_capacity->capacity_available_minutes,
            'line_efficiency' => $line_efficiency,
        ]);
    }
}