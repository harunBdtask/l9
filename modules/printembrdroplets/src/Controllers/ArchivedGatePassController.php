<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use Session, DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ArchivedBundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\ArchivedPrintInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\ArchivedPrintInventoryChallan;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory;

class ArchivedGatePassController extends Controller
{
    public function index()
    {
        $gatepass_list = ArchivedPrintInventoryChallan::with([
            'factory:id,factory_name,factory_address',
            'part:id,name'
        ])
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('printembrdroplets::pages.archived_gatepass_list', [
            'gatepass_list' => $gatepass_list,
        ]);
    }
    
    public function viewChallanWiseBundleList($challan_no)
    {
        $print_inventories = ArchivedPrintInventory::where('challan_no', $challan_no)->get();

        return view('printembrdroplets::pages.archived_challan_wise_bundle_list', [
            'print_inventories' => $print_inventories
        ]);
    }

    public function viewPrintGetpass($challan_no)
    {
        try {
            $gatePassChallan = $this->printInventoryChallan($challan_no);
            if (!$gatePassChallan) {
                Session::flash('error', 'Sorry!! This challan not found');
                return redirect()->back();
            }

            $bundleCardIds = $gatePassChallan->archived_print_inventory->pluck('bundle_card_id')->all();
            $bundleCards = ArchivedBundleCard::whereIn('id', $bundleCardIds)
                ->with([
                    'buyer:id,name',
                    'order:id,style_name',
                    'purchaseOrder:id,po_no,po_quantity',
                    'size:id,name'
                ])->get();

            $sizes = [];
            foreach ($bundleCards->groupBy('size_id') as $groupBySize) {
                $sizes[$groupBySize->first()->size_id] = $groupBySize->first()->size->name ?? 'N/A';
            }

            $cuttingNos = array_unique($bundleCards->pluck('cutting_no')->toArray());
            $lotIds = array_unique($bundleCards->pluck('lot_id')->toArray());
            $lotNos = Lot::whereIn('id', $lotIds)->pluck('lot_no')->all();
            $userFactoryInfo = $this->getUserFactory($gatePassChallan->factory_id);

            return view('printembrdroplets::pages.view_archived_print_gatepass_challan', [
                'gatePassChallan' => $gatePassChallan,
                'userFactoryInfo' => $userFactoryInfo,
                'bundleCards' => $bundleCards,
                'cuttingNos' => $cuttingNos,
                'lotNos' => $lotNos,
                'sizes' => $sizes,
            ]);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function printInventoryChallan($challan_no)
    {
        return ArchivedPrintInventoryChallan::where('challan_no', $challan_no)->with([
            'archived_print_inventory:id,challan_no,bundle_card_id',
            'part:id,name'
        ])->first();
    }

    public function search(Request $request)
    {
        if (!$request->q) {
            return redirect('/archived-gatepasses');
        }
        $gatepass_list = ArchivedPrintInventoryChallan::withoutGlobalScope('factoryId')->with([
            'factory:id,factory_name,factory_address',
            'part:id,name'
        ])
            ->join('print_factories', 'print_factories.id', '=', 'archived_print_inventory_challans.print_factory_id')
            ->join('parts', 'parts.id', '=', 'archived_print_inventory_challans.part_id')
            ->where('archived_print_inventory_challans.factory_id', factoryId())
            ->where('archived_print_inventory_challans.challan_no', 'like', '%' . $request->q . '%')
            ->orWhere('print_factories.factory_name', 'like', '%' . $request->q . '%')
            ->orWhere('print_factories.factory_address', 'like', '%' . $request->q . '%')
            ->orWhere('parts.name', 'like', '%' . $request->q . '%')
            ->select('archived_print_inventory_challans.*', 'print_factories.factory_name as factory_name', 'print_factories.factory_address as factory_address', 'parts.name as name')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('printembrdroplets::pages.archived_gatepass_list', [
            'gatepass_list' => $gatepass_list,
            'q' => $request->q,
        ]);
    }

}
