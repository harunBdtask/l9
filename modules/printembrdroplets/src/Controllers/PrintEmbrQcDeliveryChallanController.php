<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintEmbrQcDeliveryChallanService;
use Session, DB, Exception, Cache;

class PrintEmbrQcDeliveryChallanController
{
    public function index(PrintEmbrQcDeliveryChallanService $service)
    {
        if (\Request::path() == 'print-embr-qc-tag-list') {
            $type = 0; // tag;
        } else {
            $type = 1; // tag;
        }

        $challanOrTags = $service->tagChallanList($type);

        return view('printembrdroplets::pages.qc_tag_or_challan', [
            'challanOrTags' => $challanOrTags,
            'type'     => $type, 
            'q'        => request('q')
        ]);
    }

    public function challanWiseBundle($challan_no)
    {
        $inventories = PrintDeliveryInventory::where('challan_no', $challan_no)->get();

        return view('printembrdroplets::pages.delivery_challan_wise_bundle', compact('inventories'));
    }

    public function createChallanPage($challan_no)
    {   
        $parts = $this->parts();
        return view('printembrdroplets::forms.print_factory_delivery_challan', compact('challan_no','parts'));
    }

    public function createChallanPost(Request $request)
    { return $request->all();
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
                'delivery_challan_no' => $challan_no,
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
            DB::rollBack();
            session()->flash('error', 'Something Went Wrong!');
        }

        return redirect('print-factory-delivery-scan');
    }

    public function deleteDeliveryTagChallan($id, PrintEmbrQcDeliveryChallanService $service)
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
        return redirect()->back();
    }

   /* public function deleteDeliveryTagChallan($challan_no)
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
    }*/

    public function qcDeliveryChallanOrTag(Request $request)
    {
        $challanOrTag = PrintEmbroideryQcInventoryChallan::where('delivery_challan_no', $request->tag_or_challan_no)->get();

        if (!$challanOrTag->count()) {
            Session::flash('error', 'Sorry!! This is not found');
            return redirect()->back();
        }

        $tags = $challanOrTag->pluck('challan_no');
        $bundleCardIds = PrintEmbroideryQcInventory::whereIn('challan_no', $tags)->pluck('bundle_card_id')->all();
        $bundleCards = BundleCard::with([
            'buyer:id,name',
            'order:id,order_style_no,booking_no,total_quantity',
            'purchaseOrder:id,po_no,po_quantity',
            'size:id,name'
        ])
        ->whereIn('id', $bundleCardIds)
        ->get();

        $sizes = [];
        foreach ($bundleCards->groupBy('size_id') as $groupBySize) {
            $sizes[$groupBySize->first()->size_id] = $groupBySize->first()->size->name ?? 'N/A';
        }

        $cuttingNos = array_unique($bundleCards->pluck('cutting_no')->toArray());       
        $userFactoryInfo = $this->getUserFactory($challanOrTag->first()->factory_id);

        $data = [
            'challanOrTag'    => $challanOrTag->first(),
            'userFactoryInfo' => $userFactoryInfo,
            'bundleCards'     => $bundleCards,
            'cuttingNos'      => $cuttingNos,
            'sizes'           => $sizes,
            'tags'            => $tags  
        ];

        return view('printembrdroplets::pages.view_qc_delivery_tag_or_challan', $data);
    }

    public function getUserFactory($factoryId)
    {
        return Factory::whereId($factoryId)->first();
    }

    public function createDeliveryChallanFromTag(Request $request)
    {
        $delivery_challan_no = userId().time();
        $tag = PrintEmbroideryQcInventoryChallan::findOrFail($request->tagId);
        $tags = PrintEmbroideryQcInventoryChallan::where('type', 0)
            ->pluck('challan_no', 'id')
            ->prepend($tag->challan_no, $tag->id);

        $delivery_factories = Cache::get('factories');

        return view('printembrdroplets::forms.delivery_challan_from_tag', [
            'delivery_factories' => $delivery_factories,
            'delivery_challan_no' => $delivery_challan_no,
            'tags' => $tags,
            'tag' => $tag,
        ]);
    }

    public function createDeliveryChallanFromTagPost(Request $request)
    {
        $request->validate([
            'tags.*' => 'required',
            'delivery_factory_id' => 'required'
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->tags as $key => $tag) {
                PrintEmbroideryQcInventoryChallan::where('id', $tag)->update([
                    'delivery_challan_no' => $request->delivery_challan_no,
                    'delivery_factory_id' => $request->delivery_factory_id,
                    'type' => 1, // Challan
                    'delivery_status' => 1
                ]);
            }

            $bundleCards = PrintEmbroideryQcInventory::whereIn('challan_no', $request->tags)
                ->update([
                    'status' => 1
                ]);

            DB::commit();
            Session::flash('success', S_UPDATE_MSG);
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return redirect('view-qc-or-delivery-challan?tag_or_challan_no='. $request->delivery_challan_no);
        //return redirect('view-challan-or-tag?tag_or_challan='. $request->delivery_challan_no);
    }
}