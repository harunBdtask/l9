<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Washingdroplets\Models\Washing;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceive;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingInventoryChallan;
use SkylarkSoft\GoRMG\Washingdroplets\Requests\WashingChallanUpdateRequest;
use SkylarkSoft\GoRMG\Washingdroplets\Requests\WashingRequest;
use Session, DB;
use Carbon\Carbon;

class WashingChallanController extends Controller
{
    
    public function index()
    {
        $washingChallans = WashingInventoryChallan::orderBy('id', 'desc')->paginate();

        return view('washingdroplets::pages.washing_challan_list', [
           'washingChallans' => $washingChallans
        ]);
    }

    public function searchWashingChallan(Request $request)
    {
        $washingChallans = WashingInventoryChallan::where('washing_challan_no', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('washingdroplets::pages.washing_challan_list', [
           'washingChallans' => $washingChallans,
           'q' => $request->q
        ]);
    }

    public function viewWashingChallan(Request $request)
    {
        $washingChallan = WashingInventoryChallan::where('washing_challan_no', $request->washing_challan_no)->first();
       
        $floor_array = [];
        if ($washingChallan) {
            $i = 0;
            foreach($washingChallan->washings as $key => $washings) {
                if(is_array($floor_array) && !in_array($washings->sewingoutput->line->floor->floor_no,$floor_array)){
                    $floor_array[$i] = $washings->sewingoutput->line->floor->floor_no;
                }
                if($i == 0){
                    $floor_array[$i] = $washings->sewingoutput->line->floor->floor_no;
                }
                $i++;
            }
        }
        return view('washingdroplets::pages.view_washing_challan',[
            'washingChallan' => $washingChallan,
            'floor_array' => $floor_array
        ]);
    }


    public function sendBundleSewingToWashing($washing_challan_no)
    {
        /*$washing_bundles = Washing::where('washing_challan_no', $washing_challan_no)->get();
        foreach ($washing_bundles as $key => $bundle) {
            Washing::findorFail($bundle->id)->update(['status' => 1]);
        }*/

        $factories = $this->washFactories();

        return view('washingdroplets::forms.washing_send_to_factory', [
            'factories' => $factories,
            'washing_challan_no' => $washing_challan_no
        ]);       
    }

    public function sendDirectlySewingToWashingChllanWise($washing_challan_no)
    {
        /*$washing_bundles = Washing::where('washing_challan_no', $washing_challan_no)->get();
        foreach ($washing_bundles as $key => $bundle) {
            Washing::findorFail($bundle->id)->update(['status' => 1]);
        }*/

        $factories = $this->washFactories();

        return view('washingdroplets::forms.washing_send_to_factory', [
            'factories' => $factories,
            'washing_challan_no' => $washing_challan_no,
            'sewing_to_washing_status' => 1
        ]);       
    }

    public function washFactories()
    {
        return PrintFactory::where('factory_type', 'wash')
            ->pluck('factory_name', 'id')
            ->all();
    } 

    public function sentWashingFactoryPost(Request $request)
    {
        $rules = [
            'washing_challan_no' => 'required|unique:washing_inventory_challans',
            'print_wash_factory_id' => 'required',
            'bag' => 'required',
        ];
        $customMessages = [
            'print_wash_factory_id.required' => 'The wash factory field is required.'
        ];
        $this->validate($request, $rules, $customMessages);

        $wshingChallan = [
            'washing_challan_no' => $request->washing_challan_no,
            'print_wash_factory_id' => $request->print_wash_factory_id,
            'bag' => $request->bag
        ];

        try {
            DB::beginTransaction();
            // sewing_to_washing_status == 1 means directly sewing to washing
            if ($request->sewing_to_washing_status == 1) {
                $this->bundleSentSewingToWashing($request->washing_challan_no);
            }
            $challan = WashingInventoryChallan::create($wshingChallan);
            if ($challan) {
                $bundleIds = $challan->washings->pluck('bundle_card_id')->all();
                Washing::whereIn('bundle_card_id', $bundleIds)
                    ->update(['status' => 1]);

                DB::table('sewingoutputs')
                    ->whereIn('bundle_card_id', $bundleIds)
                    ->update(['status' => 1]);

                DB::table('bundle_cards')
                    ->whereIn('id', $bundleIds)
                    ->update(['washing_date' => date('Y-m-d')]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
        return redirect('view-washing-challan?washing_challan_no='.$request->washing_challan_no);
    }

     // directly sent to wshing from sewingoutput 
    public function bundleSentSewingToWashing($washing_challan_no)
    {
        // if directly sewing to washing, output_challan_no = washing_challan_no;
        $output_bundles = Sewingoutput::with('bundlecard:id,buyer_id,order_id,purchase_order_id,color_id,size_id')
            ->where('output_challan_no', $washing_challan_no)
            ->get();

        $washing_inputs = [];
        $dateTime = Carbon::now();
        foreach ($output_bundles as $key => $bundle) {
            $washing_inputs[] = [
                'bundle_card_id' => $bundle->bundle_card_id,
                'washing_challan_no' => $bundle->output_challan_no,
                'buyer_id' => $bundle->bundlecard->buyer_id,
                'order_id' => $bundle->bundlecard->order_id,
                'purchase_order_id' => $bundle->purchase_order_id,
                'color_id' => $bundle->color_id,
                'size_id' => $bundle->bundlecard->size_id,
                'status' => 1,
                'user_id' => userId(),
                'factory_id' => $bundle->factory_id,
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ];
        }
        Washing::insert($washing_inputs);

        return true;
    }

    public function edit($id)
    {
        $washing_challan = WashingInventoryChallan::find($id);
        $washing_factories = PrintFactory::where('factory_type', 'wash')
            ->pluck('factory_name', 'id')
            ->all();

        return view('washingdroplets::forms.washing_challan_edit',[
            'washing_challan' => $washing_challan,
            'washing_factories' => $washing_factories
        ]);
    }

    public function update($id, WashingChallanUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $washing_challan = WashingInventoryChallan::findOrFail($id);
            $washing_challan->print_wash_factory_id = $request->print_wash_factory_id;
            $washing_challan->bag = $request->bag;
            $washing_challan->save();
            DB::commit();
            Session::flash('success', 'Data Updated successfully!!');
            return redirect('/washing-challan-list');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!ERROR CODE Wash Challan.U-102');
            return redirect()->back();
        }
    }

}
