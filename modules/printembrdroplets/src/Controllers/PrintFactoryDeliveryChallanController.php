<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintDeliveryChallanService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;

class PrintFactoryDeliveryChallanController
{
    public function index(PrintDeliveryChallanService $service)
    {
        $challans = $service->deliveryChallanList();

        return view('printembrdroplets::pages.delivery-list', [
            'challans' => $challans,
            'q'        => request('q')
        ]);
    }

    public function challanWiseBundle($challan_no)
    {
        $inventories = PrintDeliveryInventory::where('challan_no', $challan_no)->get();

        return view('printembrdroplets::pages.delivery_challan_wise_bundle', compact('inventories'));
    }

    public function parts()
    {
        return Part::pluck('name', 'id')->all();
    }

    public function createChallanPage($challan_no)
    {   
        $parts = $this->parts();
        return view('printembrdroplets::forms.print_factory_delivery_challan', compact('challan_no','parts'));
    }

    public function createChallanPost(Request $request)
    {
        $request->validate([
            'challan_no' => 'required|unique:print_delivery_inventory_challans',
            'part_id' => 'required|numeric',
            'bag' => 'required|numeric'
        ]);

        $challan_no = $request->input('challan_no');

        try {
            $challanCreated = PrintDeliveryInventoryChallan::where('challan_no', $challan_no)->first();

            if ($challanCreated) {
                Session::flash('error', 'Already exists this challan!');
                return redirect()->back();
            }

            DB::beginTransaction();
            $challan = PrintDeliveryInventoryChallan::create([
                'challan_no' => $challan_no,
                'bag' => $request->bag
            ]);
            if ($challan->print_delivery_inventories) {
                foreach ($challan->print_delivery_inventories as $inv) {
                    $inv->status = 1;
                    $inv->save();
                }
            }
            DB::commit();
            session()->flash('success', 'Challan Created Successfully!');
            return redirect('delivery-challan/' . $challan_no .'/view');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            session()->flash('error', 'Something Went Wrong!');
        }

        return redirect('print-factory-delivery-scan');
    }

    public function viewChallan($challan_no, PrintDeliveryChallanService $service)
    {dd(999);
        $challan = $service->deliveryChallanForPrintView($challan_no);
dd( $challan);
        if ($challan === null) {
            Session::flash('error', 'Sorry!! This challan not found');
            return redirect()->back();
        }

        $bundleCardIds = $challan->bundleCardIds();
        $bundleCards = $service->bundlesForPrintChallan($bundleCardIds);

        $sizes = [];
        foreach ($bundleCards->groupBy('size_id') as $groupBySize) {
            $sizes[$groupBySize->first()->size_id] = $groupBySize->first()->size->name ?? 'N/A';
        }

        $cuttingNos = array_unique($bundleCards->pluck('cutting_no')->toArray());
        $lotIds = array_unique($bundleCards->pluck('lot_id')->toArray());
        $lotNos = Lot::whereIn('id', $lotIds)->pluck('lot_no')->all();
        $userFactoryInfo = $service->getUserFactory($challan->factory_id);

        $data = [
            'gatePassChallan' => $challan,
            'userFactoryInfo' => $userFactoryInfo,
            'bundleCards'     => $bundleCards,
            'cuttingNos'      => $cuttingNos,
            'lotNos'          => $lotNos,
            'sizes'           => $sizes,
        ];

        return view('printembrdroplets::pages.view_print_delivery_challan', $data);
    }

    public function deletePrintInventoryBundle($id)
    {
        try {
            $delivery = PrintDeliveryInventory::findOrFail($id);
            $bundle = BundleCard::findOrFail($delivery->bundle_card_id);

            DB::beginTransaction();
            $bundle->print_factory_delivery_rejection = 0;
            $bundle->save();
            $delivery->delete();
            DB::commit();

            return SUCCESS;
        } catch (Exception $e) {
            DB::rollBack();
            return FAIL;
        }
    }

    public function deleteChallan($challan_no)
    {

        try {
            DB::beginTransaction();
            PrintDeliveryInventoryChallan::where('challan_no', $challan_no)->delete();
            DB::commit();
            session()->flash('success', 'Challan Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something Went Wrong!');
        }

        return redirect('print-factory-delivery-list');
    }    

    /*public function viewPrintChallan($challan_no, PrintDeliveryChallanService $service)
    {
        $challan = $service->deliveryChallanForPrintView($challan_no);

        if ($challan === null) {
            Session::flash('error', 'Sorry!! This challan not found');
            return redirect()->back();
        }

        $bundleCardIds = $challan->bundleCardIds();
        $bundleCards = $service->bundlesForPrintChallan($bundleCardIds);

        $sizes = [];
        foreach ($bundleCards->groupBy('size_id') as $groupBySize) {
            $sizes[$groupBySize->first()->size_id] = $groupBySize->first()->size->name ?? 'N/A';
        }

        $cuttingNos = array_unique($bundleCards->pluck('cutting_no')->toArray());
        $lotIds = array_unique($bundleCards->pluck('lot_id')->toArray());
        $lotNos = Lot::whereIn('id', $lotIds)->pluck('lot_no')->all();
        $userFactoryInfo = $service->getUserFactory($challan->factory_id);

        $data = [
            'gatePassChallan' => $challan,
            'userFactoryInfo' => $userFactoryInfo,
            'bundleCards'     => $bundleCards,
            'cuttingNos'      => $cuttingNos,
            'lotNos'          => $lotNos,
            'sizes'           => $sizes,
        ];

        return view('printembrdroplets::pages.view_print_delivery_challan', $data);
    }*/
}