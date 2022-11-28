<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventoryChallan;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class PrintEmbrQcController extends Controller
{
    public $active;
    public $inactive;
    public $print_production;

    public function __construct()
    {
        $this->active = ACTIVE;
        $this->inactive = INACTIVE;
        $this->print_production = 2;
    }

    public function printEmbrQcScan()
    {
        $challan_no = $this->getChallanNo();
        $bundle_info = $this->challanWiseBundles($challan_no);
        $data = compact('challan_no', 'bundle_info');

        return view('printembrdroplets::forms.print_embr_qc_scan', $data);
    }

    public function getChallanNo()
    {
        $challan = PrintEmbroideryQcInventory::where([
            'status' => $this->inactive,
            'created_by' => userId()
        ])->first();

        return $challan->challan_no ?? userId() . time();
    }

    private function challanWiseBundles($challan_no)
    {
        return PrintEmbroideryQcInventory::with([
            'bundle_card:id,bundle_no,suffix,cutting_no,quantity,total_rejection,print_factory_receive_rejection,print_rejection,embroidary_rejection,buyer_id,order_id,purchase_order_id,color_id,size_id,production_rejection_qty,qc_rejection_qty',
            'bundle_card.order:id,order_style_no,booking_no',
            'bundle_card.purchaseOrder:id,po_no,po_quantity',
            'bundle_card.color:id,name',
            'bundle_card.size:id,name'
        ])
            ->where('challan_no', $challan_no)
            ->orderby('updated_at', 'desc')
            ->get();
    }

    public function printEmbrQcScanPost(Request $request)
    {
        try {

            $bundleCard = BundleCard::with([
                'buyer:id,name',
                'order:id,order_style_no,booking_no',
                'purchaseOrder:id,po_no',
                'color:id,name',
                'size:id,name',
                'print_production:id,bundle_card_id,production_status',
                'print_embr_qc_inventory:id,bundle_card_id'
            ])->where([
                'id' => substr($request->bundle_card_id, 1, 9),
                'status' => $this->active
            ])->first();

            if ($bundleCard) {
                if (!$bundleCard->print_embr_qc_inventory) {
                    if ($bundleCard->print_production && $bundleCard->print_production->production_status == $this->print_production) {
                        $qcInventory = PrintEmbroideryQcInventory::create([
                            'bundle_card_id' => $bundleCard->id,
                            'challan_no' => $request->challan_no
                        ]);

                        if ($qcInventory) {

                            $bundleQty = $bundleCard->quantity -
                                ($bundleCard->total_rejection + $bundleCard->print_factory_receive_rejection + $bundleCard->production_rejection_qty);
                            DB::table('print_factory_reports')->where('bundle_card_id', $bundleCard->id)
                                ->update(['qc_qty' => $bundleQty]);

                            $status = 0;
                        }

                        if (substr($request->bundle_card_id, 0, 1) == 1) {
                            $rejection_status = 1; // For rejection bundle scan
                        }


                    } else {
                        $message = 'Sorry!! This bundle does exist in print production';
                    }
                } else {
                    $message = 'Sorry!! Already scan this bundle';
                }
            } else {
                $message = 'Invalid bundle!! Please scan valid bundle';
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json([
            'status' => $status ?? 1,
            'message' => $message ?? '',
            'rejection_status' => $rejection_status ?? 0,
            'bundle_info' => $bundleCard ?? null
        ]);
    }

    public function createPrintEmbrQcTag($challan_no)
    {
        try {
            DB::beginTransaction();

            $with = 'print_received_invetory.print_receive_inventory_challan';
            $qcInventory = PrintEmbroideryQcInventory::with($with)
                ->where('challan_no', $challan_no)->first();

            if ($qcInventory) {
                $tag = PrintEmbroideryQcInventoryChallan::create([
                    'challan_no' => $challan_no,
                    'type' => 0, // 0 = tag
                ]);
                $tag->print_embroidery_qc_inventories()->update([
                    'status' => 1
                ]);

                DB::commit();
                session()->flash('success', 'Created Successfully!');
                return redirect('view-challan-or-tag?tag_or_challan=' . $challan_no);
            } else {
                session()->flash('success', 'Sorry!! Please scan at least one bundle');
            }

        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            DB::rollback();
        }
        return redirect()->back();
    }

    public function createPrintEmbrDeliveryChallan($challan_no)
    {
        $delivery_factories = Factory::pluck('factory_name', 'id')->all();

        return view('printembrdroplets::forms.print_embr_delivery_factory', compact('delivery_factories', 'challan_no'));
    }

    public function createPrintEmbrDeliveryChallanPost(Request $request)
    {
        $request->validate([
            'delivery_factory_id' => 'required',
            'remarks' => 'nullable|max:120'
        ]);

        try {
            DB::beginTransaction();
            $with = 'print_received_invetory.print_receive_inventory_challan';
            $qcInventory = PrintEmbroideryQcInventory::with($with)
                ->where('challan_no', $request->challan_no)->first();

            if ($qcInventory) {
                $tag = PrintEmbroideryQcInventoryChallan::create([
                    'challan_no' => $request->challan_no,
                    'delivery_challan_no' => $request->challan_no,
                    'type' => 1, // 1 = Challan
                    'remarks' => $request->remarks,
                    'delivery_status' => ACTIVE
                ]);
                $tag->print_embroidery_qc_inventories()->update([
                    'status' => 1
                ]);

                DB::commit();
                session()->flash('success', 'Created Successfully!');
                return redirect('view-qc-or-delivery-challan?tag_or_challan_no=' . $request->challan_no);
            } else {
                session()->flash('success', 'Sorry!! Please scan at least one bundle');
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            DB::rollback();
        }
        return redirect()->back();
    }

    /*public function qcDeliveryChallanOrTag(Request $request)
    {        
        $challanOrTag = PrintEmbroideryQcInventoryChallan::with('delivery_factory')
            ->where('challan_no', $request->tag_or_challan)->first();

        if ($challanOrTag == null) {
            Session::flash('error', 'Sorry!! This is not found');
            return redirect()->back();
        }

        $bundleCardIds = $challanOrTag->print_embroidery_qc_inventories->pluck('bundle_card_id')->all();
        $bundleCards = BundleCard::whereIn('id', $bundleCardIds)
            ->with([
                'buyer:id,name',
                'order:id,order_style_no,booking_no,total_quantity',
                'purchaseOrder:id,po_no,po_quantity',
                'size:id,name'
            ])->get();

        $sizes = [];
        foreach ($bundleCards->groupBy('size_id') as $groupBySize) {
            $sizes[$groupBySize->first()->size_id] = $groupBySize->first()->size->name ?? 'N/A';
        }

        $cuttingNos = array_unique($bundleCards->pluck('cutting_no')->toArray());       
        $userFactoryInfo = $this->getUserFactory($challanOrTag->factory_id);

        $data = [
            'challanOrTag'    => $challanOrTag,
            'userFactoryInfo' => $userFactoryInfo,
            'bundleCards'     => $bundleCards,
            'cuttingNos'      => $cuttingNos,
            'sizes'           => $sizes,
        ];

        return view('printembrdroplets::pages.view_qc_delivery_tag_or_challan', $data);
    }*/

    public function getUserFactory($factoryId)
    {
        return Factory::whereId($factoryId)->first();
    }
}