<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\ASIConsumption;

use App\Http\Controllers\Controller;
use PDF;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\ASIConsumption\ASIConsumption;
use SkylarkSoft\GoRMG\Merchandising\Models\ASIConsumption\AsiConsumptionDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\ASIConsumptionSummaryReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class ASIConsumptionController extends Controller
{

    public function index()
    {
        $consumptions = ASIConsumption::with('factory:id,factory_name', 'buyer:id,name', 'season:id,season_name')
            ->orderByDesc('id')->get()->paginate();

        return view('merchandising::asi-consumption.index', compact('consumptions'));
    }

    public function filter(Request $request)
    {
        $q = '%' . $request->search . '%';
        $consumptions = ASIConsumption::query()
            ->where('unique_id', 'LIKE', $q)
            ->orWhere('style_name', 'LIKE', $q)
            ->orWhereHas('buyer', function ($query) use ($q) {
                return $query->where('name', 'LIKE', $q);
            })
            ->orWhereHas('season', function ($query) use ($q) {
                return $query->where('season_name', 'LIKE', $q);
            })
            ->orWhereHas('factory', function ($query) use ($q) {
                return $query->where('factory_name', 'LIKE', $q);
            })
            ->get()->paginate();

        return view('merchandising::asi-consumption.index', compact('consumptions'));
    }

    public function create()
    {
        return view('merchandising::asi-consumption.create_update');

    }

    public function edit()
    {
        return view('merchandising::asi-consumption.create_update');
    }

    public function store(Request $request)
    {
        $consumptions = new ASIConsumption($request->all());
        $consumptions->save();

        return response()->json($consumptions);
    }

    public function update(ASIConsumption $ASIConsumption, Request $request)
    {
        $ASIConsumption->fill($request->all());
        $ASIConsumption->save();

        return response()->json($ASIConsumption);
    }

    public function destroy(ASIConsumption $ASIConsumption)
    {
        try {
            DB::beginTransaction();
            $ASIConsumption->delete();
            $ASIConsumption->details()->delete();
            DB::commit();

            Session::flash('success', 'Data Deleted successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            Session::flash('error', "Something went wrong!{$e->getMessage()}");
        }

        return redirect()->back();

    }

    public function show(ASIConsumption $ASIConsumption)
    {
        $asiConsumption = $ASIConsumption
            ->load('details', 'details.gmtsItem:id,name', 'details.embellishmentName:id,name', 'details.embellishmentType:id,type',
                'details.fabrication', 'details.uom:id,unit_of_measurement');

        return [
            'id' => $asiConsumption->id,
            'factory_id' => $asiConsumption->factory_id,
            'season_id' => $asiConsumption->season_id,
            'buyer_id' => $asiConsumption->buyer_id,
            'style_name' => $asiConsumption->style_name,
            'unique_id' => $asiConsumption->unique_id,
            'created_date' => $asiConsumption->created_date,
            'updated_date' => $asiConsumption->updated_date,
            'details' => $asiConsumption->details->map(function ($detail) {
                return [
                    'asi_consumption_id' => $detail->asi_consumption_id,
                    'body_part_id' => $detail->body_part_id,
                    'cons_per_dzn' => $detail->cons_per_dzn,
                    'cons_per_pcs' => $detail->cons_per_pcs,
                    'efficiency' => $detail->efficiency,
                    'embellishment_name' => $detail->embellishmentName,
                    'embellishment_type' => $detail->embellishmentType,
                    'embl_id' => $detail->embl_id,
                    'fabric_dia' => $detail->fabric_dia,
                    'fabrication' => FabricDescriptionService::description($detail->fabrication_id) ?? null,
                    'fabrication_id' => $detail->fabrication_id,
                    'gmts_item' => $detail->gmtsItem,
                    'gmts_item_id' => $detail->gmts_item_id,
                    'group_id' => $detail->group_id,
                    'id' => $detail->id,
                    'length' => $detail->length,
                    'marker_type' => $detail->marker_type,
                    'remarks' => $detail->remarks,
                    'type_id' => $detail->type_id,
                    'uom' => $detail->uom,
                    'uom_id' => $detail->uom_id,
                    'width' => $detail->width,
                ];
            }),
        ];
//        return response()->json($asiConsumption);
    }

    public function storeDetails(Request $request)
    {
        $asiConsumptionId = $request->get('id');
        $consumptionDetails = AsiConsumptionDetails::findOrNew($asiConsumptionId ?? null);
        $consumptionDetails->fill($request->all());
        $consumptionDetails->save();

        return response()->json($consumptionDetails);
    }

    public function deleteDetails(AsiConsumptionDetails $asiConsumptionDetails)
    {
        $asiConsumptionDetails->delete();
        $message = 'success';

        return response()->json($message);
    }

    public function summaryReport(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $buyers = $factoryId ? Buyer::query()->where('factory_id', $factoryId)->get() : [];
        $seasons = ($buyerId && $factoryId) ? Season::query()->where("factory_id", $factoryId)->where("buyer_id", $buyerId)->get() : [];
        $factories = Factory::all();
//        $groups = AsiConsumptionDetails::groups;
        $groups = FabricNature::all(['id', 'name']);
        $consumptions = null;
        if ($factoryId) {
            $consumptions = ASIConsumptionSummaryReportService::reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName);
            collect($consumptions)->each(function ($itemDetails) {
                collect($itemDetails->details)->each(function (&$item) {
                    $item['fabric_description'] = collect(FabricDescriptionService::description($item->fabrication->id))->implode(', ') ?? null;
                });
            });
        }

        $style_name = [];
        if ($factoryId && $buyerId && $seasonId) {
            $style_name = ASIConsumption::query()
                ->where([
                    "factory_id" => $factoryId,
                    "buyer_id" => $buyerId,
                    "season_id" => $seasonId
                ])->pluck('style_name');
        }

        return view('merchandising::asi-consumption.summary-report.view', compact('factories', 'factoryId',
            'buyerId', 'buyers', 'seasons', 'seasonId', 'fromDate', 'toDate', 'consumptions', 'groups', 'style_name'));


    }

    public function summaryReportPdf(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $groups = FabricNature::all(['id', 'name']);
        $consumptions = null;
        if ($factoryId) {
            $consumptions = ASIConsumptionSummaryReportService::reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName);
            collect($consumptions)->each(function ($itemDetails) {
                collect($itemDetails->details)->each(function (&$item) {
                    $item['fabric_description'] = collect(FabricDescriptionService::description($item->fabrication->id))->implode(', ') ?? null;
                });
            });
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::asi-consumption.summary-report.pdf',
            compact('groups', 'consumptions')
        )->setPaper('a3')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('new-asi-consumption-report.pdf');
    }

    public function summeryReportPrint(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $groups = FabricNature::all(['id', 'name']);
        $consumptions = null;
        if ($factoryId) {
            $consumptions = ASIConsumptionSummaryReportService::reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName);
            collect($consumptions)->each(function ($itemDetails) {
                collect($itemDetails->details)->each(function (&$item) {
                    $item['fabric_description'] = collect(FabricDescriptionService::description($item->fabrication->id))->implode(', ') ?? null;
                });
            });
        }

        return view('merchandising::asi-consumption.summary-report.print', compact('factoryId',
            'buyerId', 'seasonId', 'fromDate', 'toDate', 'consumptions', 'groups'));
    }

    public function getBuyers(Request $request): JsonResponse
    {
        $data = Buyer::where('factory_id', $request->get('factoryId'))->get();

        return response()->json($data);
    }

    public function loadSeasons(Request $request): JsonResponse
    {
        $data = Season::where('buyer_id', $request->get('buyerId'))->get();

        return response()->json($data);
    }

    public function getStyle(Request $request)
    {
        $factory_id = $request->get("factoryId");
        $buyer_id = $request->get("buyerId") ?? null;
        $season_id = $request->get("seasonId");

        $style = ASIConsumption::query()
            ->where([
                "factory_id" => $factory_id,
                "buyer_id" => $buyer_id,
                "season_id" => $season_id
            ])->get('style_name');

//        $po_no = PurchaseOrder::where("order_id", $order->id)->pluck("po_no", "po_no")->prepend("All Po", 'All');
        $response = [
            "style_name" => $style->values(),
        ];

        return response()->json($response, Response::HTTP_OK);

    }
}
