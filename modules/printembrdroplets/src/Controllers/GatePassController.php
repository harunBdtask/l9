<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Printembrdroplets\Notifications\PrintSendChallanCutManagerNotification;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintSendCacheKeyService;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class GatePassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $gatepass_list = PrintInventoryChallan::with([
            'factory:id,factory_name,factory_address',
            'part:id,name'
        ])
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('printembrdroplets::pages.gatepass_list', [
            'gatepass_list' => $gatepass_list,
        ]);
    }

    public function getUserFactory($factoryId)
    {
        return Factory::whereId($factoryId)->first();
    }

    public function printEmbroideryFactories()
    {
        return PrintFactory::where('factory_type', 'print')
            ->pluck('factory_name', 'id')
            ->all();
    }

    public function parts()
    {
        return Part::pluck('name', 'id')->all();
    }

    public function sendToPrintFactory($challan_no)
    {
        $factories = $this->printEmbroideryFactories();
        $parts = $this->parts();

        return view('printembrdroplets::forms.print_send_to_factory', [
            'challan_no' => $challan_no,
            'factories' => $factories,
            'parts' => $parts
        ]);
    }

    public function sendToPrint(Request $request)
    {
        $request->validate([
            'challan_no' => 'required|unique:print_inventory_challans',
            'operation_name' => 'required',
            'factory_id' => 'required',
            'bag' => 'required',
            'part_id' => 'required'
        ]);

        try {
            $isExist = PrintInventoryChallan::where([
                'challan_no' => $request->challan_no,
                'status' => 1
            ])->count();

            if ($isExist) {
                Session::flash('error', 'Already exists this challan!');
                return redirect()->back();
            }

            $printInventories = PrintInventory::where('challan_no', $request->challan_no)->count();
            if (!$printInventories) {
                Session::flash('error', 'Please scan at least one bundle');
                return redirect()->back();
            }

            DB::transaction(function () use ($request) {
                $input = [
                    'challan_no' => $request->challan_no,
                    'status' => ACTIVE,
                    'part_id' => $request->part_id,
                    'bag' => $request->bag,
                    'print_factory_id' => $request->factory_id,
                    'operation_name' => $request->operation_name
                ];

                $printInvChallan = PrintInventoryChallan::create($input);
                if ($printInvChallan->print_inventory) {
                    if ($request->operation_name == PRNT) {
                        $bundle_send_date_column = 'print_sent_date';
                        $type = 'print';
                    } else {
                        $bundle_send_date_column = 'embroidary_sent_date';
                        $type = 'embroidery';
                    }
                    // update for print send
                    $printInvChallan->print_inventory()->update([
                        'status' => 1,
                        'type' => $type
                    ]);

                    // update bundle position for reports
                    $printSendBundles = $printInvChallan->print_inventory->pluck('bundle_card_id')->all();
                    DB::table('bundle_cards')->whereIn('id', $printSendBundles)
                        ->update([
                            $bundle_send_date_column => $printInvChallan->updated_at->toDateString()
                        ]);
                    (new PrintSendCacheKeyService())->removeCache();
                    $printInvChallan = PrintInventoryChallan::where('challan_no', $request->challan_no)->first();
                    $approvalUser = Approval::query()
                        ->where('page_name', Approval::PRINT_SEND_CHALLAN_APPROVAL_CUT_MANAGER)
                        ->orderBy('priority', 'asc')
                        ->first();
                    if ($approvalUser) {
                        $userIds = array_unique([
                            $approvalUser->user_id,
                            $approvalUser->alternative_user_id,
                        ]);
                        $users = User::whereIn('id', $userIds)->get();
                        Notification::send($users, new PrintSendChallanCutManagerNotification($printInvChallan));
                    }
                }
            });
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
        return redirect('view-print-getapass/' . $request->challan_no);
    }

    public function printInventoryChallan($challan_no)
    {
        return PrintInventoryChallan::where('challan_no', $challan_no)->with([
            'print_inventory:id,challan_no,bundle_card_id',
            'part:id,name'
        ])->first();
    }

    public function viewPrintGetpass($challan_no)
    {
        try {

            $gatePassChallan = $this->printInventoryChallan($challan_no);
            if (!$gatePassChallan) {
                Session::flash('error', 'Sorry!! This challan not found');
                return redirect()->back();
            }

            $bundleCardIds = $gatePassChallan->print_inventory->pluck('bundle_card_id')->all();
            $bundleCards = BundleCard::whereIn('id', $bundleCardIds)
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

            return view('printembrdroplets::pages.view_print_gatepass_challan', [
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

    public function viewChallanWiseBundleList($challan_no)
    {
        $print_inventories = PrintInventory::where('challan_no', $challan_no)->get();

        return view('printembrdroplets::pages.challan_wise_bundle_list', [
            'print_inventories' => $print_inventories
        ]);
    }

    public function viewChallanWiseDeletedBundleList($challan_no)
    {
        $print_inventories = DB::table('print_inventories')
            ->leftJoin('users', 'users.id', 'print_inventories.deleted_by')
            ->leftJoin('bundle_cards', 'bundle_cards.id', 'print_inventories.bundle_card_id')
            ->leftJoin('bundle_card_generation_details', 'bundle_card_generation_details.id', 'bundle_cards.bundle_card_generation_detail_id')
            ->leftJoin('buyers', 'buyers.id', 'bundle_cards.buyer_id')
            ->leftJoin('orders', 'orders.id', 'bundle_cards.order_id')
            ->leftJoin('purchase_orders', 'purchase_orders.id', 'bundle_cards.purchase_order_id')
            ->leftJoin('colors', 'colors.id', 'bundle_cards.color_id')
            ->leftJoin('sizes', 'sizes.id', 'bundle_cards.size_id')
            ->leftJoin('lots', 'lots.id', 'bundle_cards.lot_id')
            ->selectRaw(
                'print_inventories.challan_no,bundle_cards.id, bundle_card_generation_details.is_manual,
                bundle_cards.bundle_no,bundle_cards.size_wise_bundle_no,bundle_cards.suffix,bundle_cards.serial,bundle_cards.cutting_no,bundle_cards.quantity,bundle_cards.total_rejection,bundle_cards.print_rejection,bundle_cards.embroidary_rejection,bundle_cards.sewing_output_date,
                buyers.name as buyer_name,
                orders.booking_no,orders.style_name,
                purchase_orders.po_no,purchase_orders.po_quantity,
                colors.name as color_name,
                sizes.id as size_id,
                sizes.name as size_name,
                lots.lot_no,
                print_inventories.deleted_at,
                CONCAT(users.first_name," ",users.last_name) as deleted_by'
            )
            ->where('print_inventories.challan_no', $challan_no)
            ->whereNotNull('print_inventories.deleted_at')
            ->get();

        return view('printembrdroplets::pages.challan_wise_deleted_bundle_list', [
            'challan_no' => $challan_no,
            'print_inventories' => $print_inventories,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $gatepass = PrintInventoryChallan::findOrFail($id);
            $gatepass->delete();
            DB::commit();
            Session::flash('success', 'Deleted Successfully');
            return redirect('gatepasses');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('success', $e->getMessage());
            return redirect('gatepasses');
        }
    }

    public function getSecurityGatepass($challan_id)
    {
        $challan = PrintInventoryChallan::findOrFail($challan_id);
        return view('printembrdroplets::forms.security-drop-down', compact('challan'));
    }

    public function getSecurityGatepassPost(Request $request)
    {
        try {
            DB::beginTransaction();
            switch ($request->security_staus) {
                case 'send':
                    $security_status = 1;
                    break;
                case 'hold':
                    $security_status = 2;
                    break;
                case 'cancel':
                    $security_status = 3;
                    break;
                default:
                    $security_status = 0;
                    break;
            }
            DB::table('print_inventory_challans')
                ->where('id', $request->id)
                ->update([
                    'security_status' => $security_status,
                    'updated_by' => userId()
                ]);
            DB::commit();

            Session::flash('success', 'Successfully updated');
            return redirect('gatepasses');
        } catch (\Exception $e) {
            DB::rollback();
            Session::falsh('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function deletePrintInventoryBundle($id)
    {
        try {
            DB::beginTransaction();
            $print_inventory = PrintInventory::findorFail($id);
            if ($print_inventory->cuttingInventory && $print_inventory->cuttingInventory->count()) {
                return response()->json([
                    'status' => FAIL,
                    'message' => 'Cannot delete because this bundle is already received!'
                ]);
            }
            if ($print_inventory->delete()) {
                DB::commit();
                return response()->json([
                    'status' => SUCCESS,
                    'message' => 'Successfully deleted!'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'status' => FAIL,
                    'message' => 'Something went wrong!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => FAIL,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function searchGatepassList(Request $request)
    {
        $gatepass_list = PrintInventoryChallan::withoutGlobalScope('factoryId')->with([
            'factory:id,factory_name,factory_address',
            'part:id,name'
        ])
            ->join('print_factories', 'print_factories.id', '=', 'print_inventory_challans.print_factory_id')
            ->join('parts', 'parts.id', '=', 'print_inventory_challans.part_id')
            ->where('print_inventory_challans.factory_id', factoryId())
            ->where('print_inventory_challans.challan_no', 'like', '%' . $request->q . '%')
            ->orWhere('print_factories.factory_name', 'like', '%' . $request->q . '%')
            ->orWhere('print_factories.factory_address', 'like', '%' . $request->q . '%')
            ->orWhere('parts.name', 'like', '%' . $request->q . '%')
            ->select('print_inventory_challans.*', 'print_factories.factory_name as factory_name', 'print_factories.factory_address as factory_address', 'parts.name as name')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('printembrdroplets::pages.gatepass_list', [
            'gatepass_list' => $gatepass_list,
            'q' => $request->q,
        ]);
    }
}
