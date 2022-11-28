<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintDeliveryInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintReceiveChallanService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactoryTable;

class PrintFactoryChallanController extends Controller
{

    public function index(PrintReceiveChallanService $service)
    {        
        $path = \Request::path();
        if ($path == 'print-embr-factory-receive-challan-list') {
            $type = 0; // challan
        } else {
            $type = 1; // tag
        }

        $challans = $service->challanList($type);

        return view('printembrdroplets::pages.factory_receive_challan_tag_list', [
            'challans' => $challans,
            'type'     => $type,
            'q'        => request('q')
        ]);
    }

    public function createFactoryReceivedChallan($challan_no)
    {
        $tables = PrintFactoryTable::printFactoryTables();

        return view('printembrdroplets::forms.print_factory_rcv_challan', compact('tables', 'challan_no'));
    }    

    public function createFactoryReceiveTag($challan_no)
    {
        try {            
            DB::beginTransaction();
            $print_receive_inventory = PrintReceiveInventory::where('challan_no', $challan_no)->first();
            if ($print_receive_inventory) {
                $tag = PrintReceiveInventoryChallan::create([
                    'challan_no' => $challan_no,
                    'type' => 1 // 1 = tag
                ]);
                $tag->print_receive_inventories()->update([
                    'status' => 1
                ]);
                
                DB::commit();
                session()->flash('success', 'Created Successfully!');
                return redirect('receive-challan-tag/' . $challan_no .'/view');
            } else {
                session()->flash('success', 'Sorry!! Please scan at least one bundle');
            }            
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    public function createReceivedChallanFromTag(Request $request)
    {
        $challan_no = $request->tagId;
        $tables = PrintFactoryTable::printFactoryTables();

        return view('printembrdroplets::forms.tag_to_print_factory_rcv_challan', compact('tables', 'challan_no'));
    }

    public function createReceivedChallanFromTagPost(Request $request)
    {
        $request->validate([            
            'operation_name' => 'required',
            'table_id'       => 'required',
        ]);

        try {
            DB::beginTransaction();
            $challan = PrintReceiveInventoryChallan::with('print_receive_inventories')->where([
                'id' => $request->tag_id,
                'type' => PrintReceiveInventoryChallan::TAG
            ])->first();
          
            if ($challan && $challan->print_receive_inventories) {
                $input = [
                    'operation_name' => $request->operation_name,
                    'table_id' => $request->table_id,
                    'type' => PrintReceiveInventoryChallan::CHALLAN
                ];
                $challan = PrintReceiveInventoryChallan::findOrFail($request->tag_id);
                $challanOnj = clone $challan;
                $challanOnj->update($input);
                $challan->print_receive_inventories()->update(['status' => 1]);
                DB::commit();

                session()->flash('success', 'Challan Created Successfully!');
                return redirect('receive-challan-tag/' . $challan->challan_no .'/view');
            }
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        session()->flash('error', 'Not found this tag!');
        return redirect()->back();
    }

    public function createFactoryReceiveChallanPost(Request $request)
    {
        $request->validate([
            'challan_no'     => 'required|unique:print_receive_inventory_challans',
            'operation_name' => 'required',
            'table_id'       => 'required',
        ]);

        $challan_no = $request->challan_no;       

        try {
            $challanAlreadyCreated = PrintReceiveInventoryChallan::where('challan_no', $challan_no)->first();
            if ($challanAlreadyCreated) {
                Session::flash('error', 'Already exists this challan!');
                return redirect()->back();
            }

            DB::beginTransaction();
            $input = [
                'challan_no' => $request->challan_no,
                'operation_name' => $request->operation_name,
                'table_id' => $request->table_id,
                'type' => PrintReceiveInventoryChallan::CHALLAN
            ];         
            $challan = PrintReceiveInventoryChallan::create($input);           
            $data =$challan->print_receive_inventories()->update(['status' => 1]);          
            DB::commit();

            session()->flash('success', 'Challan Created Successfully!');
            return redirect('receive-challan-tag/' . $challan_no .'/view');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function edit($challan_no)
    {
        $tables = PrintFactoryTable::pluck('name', 'id');
        $challan = PrintReceiveInventoryChallan::where('challan_no', $challan_no)->first();

        return view('printembrdroplets::forms.rcv_challan_edit', compact('tables', 'challan_no', 'challan'));
    }

    public function update($challan_no, Request $request)
    {
        $request->validate([
            'operation_name' => 'required',
            'table_id'       => 'required'
        ]);

        $challan = PrintReceiveInventoryChallan::with('print_receive_inventories:id,bundle_card_id,challan_no')
            ->where('challan_no', $challan_no)
            ->first();

        $bundleCardIds = $challan->print_receive_inventories->pluck('bundle_card_id');
        $isAlreadyInProduction = PrintReceiveInventory::whereNotNull('production_status')
            ->whereIn('bundle_card_id', $bundleCardIds)
            ->count();

        if ($isAlreadyInProduction) {
            session()->flash('error', 'Already in producttion so you can\'t update');
            return redirect('print-embr-factory-receive-challan-list');
        }

        $challan->operation_name = $request->operation_name;
        $challan->table_id = $request->table_id;
        $challan->save();

        session()->flash('success', S_UPDATE_MSG);
        return redirect('print-embr-factory-receive-challan-list');
    }

    public function challanWiseBundle($challan_no)
    {
        $inventories = PrintReceiveInventory::where('challan_no', $challan_no)->get();

        return view('printembrdroplets::forms.rcv.challan_wise_bundle', compact('inventories'));
    }

    public function deletePrintInventoryBundle($id)
    {
        try {
            DB::beginTransaction();
            $receive = PrintReceiveInventory::findorFail($id);

            // if bundle already in print production so you can't delete
            if ($receive->production_status) {
                return FAIL; 
            }            
            $bundle = BundleCard::findorFail($receive->bundle_card_id);
            $bundle->print_factory_receive_rejection = 0;
            $bundle->print_factory_delivery_rejection = 0;
            $bundle->save();
            $receive->delete();
            DB::commit();

            return SUCCESS;
        } catch (\Exception $ex) {
            DB::rollBack();
            return FAIL;
        }
    }

    public function viewPrintRcvChallan($challan_no, PrintReceiveChallanService $service)
    {
        $challan = $service->rcvChallanForPrintView($challan_no);
        if ($challan == null) {
            Session::flash('error', 'Sorry!! This challan not found');
            return redirect()->back();
        }

        $bundleCardIds = $challan->inventories->pluck('bundle_card_id')->all();
        $bundleCards = $service->bundlesForPrintChallan($bundleCardIds);

        $sizes = [];
        foreach ($bundleCards->groupBy('size_id') as $groupBySize) {
            $sizes[$groupBySize->first()->size_id] = $groupBySize->first()->size->name ?? 'N/A';
        }

        $cuttingNos = array_unique($bundleCards->pluck('cutting_no')->toArray());
        $lotIds = array_unique($bundleCards->pluck('lot_id')->toArray());
        $lotNos = Lot::whereIn('id', $lotIds)->pluck('lot_no')->all();
        $userFactoryInfo = $this->getUserFactory($challan->factory_id);

        $data = [
            'challanOrTag'    => $challan,
            'userFactoryInfo' => $userFactoryInfo,
            'bundleCards'     => $bundleCards,
            'cuttingNos'      => $cuttingNos,
            'lotNos'          => $lotNos,
            'sizes'           => $sizes,
        ];

        return view('printembrdroplets::pages.view_print_rcv_challan', $data);
    }

    public function deleteChallan($challan_no)
    {
        try {

            DB::beginTransaction();
            $challan = PrintReceiveInventoryChallan::with('print_receive_inventories:id,bundle_card_id,challan_no')
                ->where('challan_no', $challan_no)
                ->first();

            $bundleCardIds = $challan->print_receive_inventories->pluck('bundle_card_id');
            $isAlreadyInProduction = PrintReceiveInventory::whereNotNull('production_status')
                ->whereIn('bundle_card_id', $bundleCardIds)
                ->count();

            if ($isAlreadyInProduction) {
                session()->flash('error', 'Already in producttion so you can\'t update');
                return redirect('print-embr-factory-receive-challan-list');
            }          
            
            PrintReceiveInventoryChallan::where('challan_no', $challan_no)->delete();           
            DB::commit();
            session()->flash('success', 'Challan Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something Went Wrong!');
        }

        return redirect('print-embr-factory-receive-challan-list');
    }

    public function getUserFactory($factoryId)
    {
        return Factory::whereId($factoryId)->first();
    }
}

