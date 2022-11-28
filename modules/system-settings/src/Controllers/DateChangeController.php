<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanSizeWiseInput;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Finishing;
use SkylarkSoft\GoRMG\Washingdroplets\Models\Washing;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceive;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon;
use Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateWiseCuttingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanWiseInput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintProductionReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;

class DateChangeController extends Controller
{
    public function productionDateChange()
    {
        return view('system-settings::forms.production_date_change');
    }

    public function productionDateChangePost(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        $day_name = date('l', strtotime($request->from_date));

        if (\getRole() == 'super-admin' || \getRole() == 'admin' || $day_name == 'Friday') {
            $new_date_at = date('Y-m-d h:m:s',  strtotime($request->to_date));

            try {
                // bundle_card_generation_details table update
                BundleCardGenerationDetail::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                // bundle_cards table update
                BundleCard::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                DateWiseCuttingProductionReport::withoutGlobalScope('factoryId')
                    ->whereDate('cutting_date', $request->from_date)
                    ->update(['cutting_date' => $new_date_at]);

                DateAndColorWiseProduction::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);

                DateTableWiseCutProductionReport::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);

                // cutting_inventories table update
                CuttingInventory::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                // cutting_inventory_challans table update
                CuttingInventoryChallan::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                // print_inventories table update
                PrintInventory::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                // print_inventory_challans table update
                PrintInventoryChallan::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                DateWisePrintProductionReport::withoutGlobalScope('factoryId')
                    ->whereDate('print_date', $request->from_date)
                    ->update(['print_date' => $new_date_at]);

                DateWisePrintEmbrProductionReport::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);

                DateFloorWisePrintEmbrReport::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);

                DateWiseSewingProductionReport::withoutGlobalScope('factoryId')
                    ->whereDate('sewing_date', $request->from_date)
                    ->update(['sewing_date' => $new_date_at]);

                FinishingProductionReport::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);

                DailyChallanWiseInput::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);

                DailyChallanSizeWiseInput::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);

                // sewingoutputs table update
                Sewingoutput::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                HourlySewingProductionReport::withoutGlobalScope('factoryId')
                    ->whereDate('production_date', $request->from_date)
                    ->update(['production_date' => $new_date_at]);
                // washings table update
                Washing::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                // washing_receives table update
                WashingReceive::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                // finishings table update
                Finishing::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                // poly_cartoons table update
                PolyCartoon::withoutGlobalScope('factoryId')
                    ->whereDate('created_at', $request->from_date)
                    ->orWhereDate('updated_at', $request->from_date)
                    ->update(['created_at' => $new_date_at, 'updated_at' => $new_date_at]);

                Session::flash('success', S_UPDATE_MSG);
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
            }
        } else {
            Session::flash('error', 'Only friday date changeable');
        }

        return redirect()->back();
    }
}
