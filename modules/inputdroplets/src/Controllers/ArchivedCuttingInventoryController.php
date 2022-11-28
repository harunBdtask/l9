<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Exception;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ArchivedBundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Models\ArchivedCuttingInventoryChallan;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class ArchivedCuttingInventoryController extends Controller
{
    public function getTagOrChallanList($type)
    {
        return ArchivedCuttingInventoryChallan::with([
            'line:id,line_no,floor_id',
            'line.floor:id,floor_no',
            'user:id,first_name,last_name,screen_name,email',
            'user.factory:id,factory_name',
        ])
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    public function viewChallanList()
    {
        $challan_list = $this->getTagOrChallanList(CHALLAN);

        return view('inputdroplets::pages.view_archived_challan_tag_list', [
            'title' => 'Challan List (Archived)',
            'challan_list' => $challan_list
        ]);
    }

    public function searchChallanOrTag(Request $request)
    {
        $challan_list = [];
        switch ($request->type) {
            case 'challan':
                if (!isset($request->q)) {
                    return redirect('view-archived-challan-list');
                }

                $challan_list = ArchivedCuttingInventoryChallan::withoutGlobalScope('factoryId')->with([
                    'line:id,line_no,floor_id',
                    'line.floor:id,floor_no',
                    'user:id,first_name,last_name,email,screen_name',
                    'user.factory:id,factory_name'
                ])
                    ->join('lines', 'lines.id', 'archived_cutting_inventory_challans.line_id')
                    ->join('floors', 'floors.id', 'lines.floor_id')
                    ->join('users', 'users.id', 'archived_cutting_inventory_challans.created_by')
                    ->where('archived_cutting_inventory_challans.type', $request->type)
                    ->where('archived_cutting_inventory_challans.factory_id', factoryId())
                    ->where('archived_cutting_inventory_challans.challan_no', 'like', '%' . $request->q . '%')
                    ->orWhere('lines.line_no', 'like', '%' . $request->q . '%')
                    ->orWhere('floors.floor_no', 'like', '%' . $request->q . '%')
                    ->orderBy('archived_cutting_inventory_challans.created_at', 'desc')
                    ->select('archived_cutting_inventory_challans.*', 'lines.line_no as line_no', 'floors.floor_no as floor_no', 'users.first_name', 'users.last_name')
                    ->paginate();
                break;
            case 'tag':
                if (!isset($request->q)) {
                    return redirect('view-tag-list');
                }
                $challan_list = ArchivedCuttingInventoryChallan::withoutGlobalScope('factoryId')
                    ->join('users', 'users.id', 'archived_cutting_inventory_challans.created_by')
                    ->where('archived_cutting_inventory_challans.type', $request->type)
                    ->where('archived_cutting_inventory_challans.factory_id', factoryId())
                    ->where('archived_cutting_inventory_challans.challan_no', 'like', '%' . $request->q . '%')
                    ->orderBy('archived_cutting_inventory_challans.created_at', 'desc')
                    ->select('archived_cutting_inventory_challans.*', 'users.first_name', 'users.last_name')
                    ->paginate();
                break;
        }
        return view('inputdroplets::pages.view_archived_challan_tag_list', [
            'title' => $request->type == 'challan' ? 'Challan List (Archived)' : 'Tag List',
            $request->type == 'challan' ? 'challan_list' : 'tag_list' => $challan_list,
            'q' => $request->q
        ]);
    }

    public function viewInputChallanWiseBundlesList($challan_no)
    {
        $challan_info = $this->getBundleList($challan_no, CHALLAN);

        return view('inputdroplets::pages.input_archived_challan_wise_bundles', [
            'challan_info' => $challan_info,
            'title' => 'Input Challan'
        ]);
    }

    public function getBundleList($challan_no, $type)
    {
        return ArchivedCuttingInventoryChallan::with([
            'line:id,line_no,floor_id',
            'line.floor:id,floor_no',
            'archived_cutting_inventory:id,bundle_card_id,challan_no',
            'archived_cutting_inventory.archivedBundlecard:id,bundle_no,size_wise_bundle_no,suffix,serial,cutting_no,buyer_id,order_id,purchase_order_id,color_id,size_id,lot_id,quantity,total_rejection,print_rejection,embroidary_rejection,sewing_output_date',
            'archived_cutting_inventory.archivedBundlecard.details:id,is_manual',
            'archived_cutting_inventory.archivedBundlecard.buyer:id,name',
            'archived_cutting_inventory.archivedBundlecard.order:id,style_name',
            'archived_cutting_inventory.archivedBundlecard.purchaseOrder:id,po_no,po_quantity',
            'archived_cutting_inventory.archivedBundlecard.color:id,name',
            'archived_cutting_inventory.archivedBundlecard.size:id,name',
            'archived_cutting_inventory.archivedBundlecard.lot:id,lot_no'
        ])
            ->where(['challan_no' => $challan_no, 'type' => $type])
            ->first();
    }

    public function viewChallan($id)
    {
        try {
            $challan = ArchivedCuttingInventoryChallan::with([
                'line:id,line_no,floor_id',
                'line.floor:id,floor_no',
                'color:id,name'
            ])->findOrFail($id);

            $inputBundles = ArchivedBundleCard::with([
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder:id,po_no,po_quantity',
                'color:id,name',
                'size:id,name',
                'lot:id,lot_no'
            ])
                ->whereIn('id', $challan->archived_cutting_inventory->pluck('bundle_card_id'))
                ->select(
                    'id',
                    'buyer_id',
                    'order_id',
                    'purchase_order_id',
                    'color_id',
                    'size_id',
                    'cutting_no',
                    'lot_id',
                    'quantity',
                    'total_rejection',
                    'print_rejection',
                    'embroidary_rejection'
                )->get();

            $factory = $this->getChallanFactory($challan->factory_id);
            $order_ids = $inputBundles->unique('order_id')->pluck('order_id')->toArray();
            $poWiseSizeDetails = PoColorSizeBreakdown::query()
                ->whereIn('order_id', $order_ids)
                ->pluck('sizes', 'purchase_order_id')
                ->toArray();
            $cuttingNumbers = $inputBundles->pluck('cutting_no')->toArray();
            $cuttingNumbers = array_unique($cuttingNumbers);

            $lots = $inputBundles->pluck('lot.lot_no')->toArray();
            $lots = array_unique($lots);

            return view('inputdroplets::pages.view_archived_input_challan', [
                'challan' => $challan,
                'inputBundles' => $inputBundles,
                'factory' => $factory,
                'cuttingNumbers' => $cuttingNumbers,
                'lots' => $lots,
                'poWiseSizeDetails' => $poWiseSizeDetails
            ]);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    public function getChallanFactory($factoryId)
    {
        return Factory::where('id', $factoryId)->first();
    }
}