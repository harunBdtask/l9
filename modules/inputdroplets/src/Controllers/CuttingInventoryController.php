<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Merchandising\DTO\OrderReportDTO;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Actions\InputTagScanAction;
use SkylarkSoft\GoRMG\Inputdroplets\Actions\PrintRcvScanAction;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderReportService;
use SkylarkSoft\GoRMG\Inputdroplets\Actions\SolidInputScanAction;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Inputdroplets\Services\PrintRcvInputCacheKeyService;
use SkylarkSoft\GoRMG\Inputdroplets\Notifications\CuttingInventoryNotification;

class CuttingInventoryController extends Controller
{
    private $operation_date;

    public function __construct()
    {
        $this->operation_date = operationDate(); // method exist in global helper file
    }


    /**
     * @param Int $printStatus
     * @return String
     */
    private function getChallanNo(int $printStatus = 0): string
    {
        $cacheKey = (new PrintRcvInputCacheKeyService)->setItemStatus($printStatus)->getChallanNoCacheKey();

        return Cache::remember($cacheKey, getScanDataCachingTime(), function () use ($printStatus) {
            $challan = CuttingInventory::where([
                'status' => INACTIVE,
                'created_by' => userId()
            ])
                ->when($printStatus != 0, function ($query) {
                    return $query->where('print_status', '!=', 0);
                })
                ->when($printStatus == 0, function ($query) use ($printStatus) {
                    return $query->where('print_status', $printStatus);
                })
                ->first();

            return (string)($challan->challan_no ?? userId() . time());
        });
    }

    /**
     * @param String $challanNo
     * @param Int $printStatus
     */
    private function getCuttingInventoryData(string $challanNo, int $printStatus = 0)
    {
        $cacheKey = (new PrintRcvInputCacheKeyService)->setItemStatus($printStatus)->getChallanBundlesCacheKey();

        return Cache::remember($cacheKey, getScanDataCachingTime(), function () use ($challanNo, $printStatus) {
            return CuttingInventory::getChallanBundles($challanNo, $printStatus)
                ->map(function ($bundle) {
                    $bundleCard = $bundle->bundlecard;
                    return [
                        'id' => $bundle->bundle_card_id,
                        'quantity' => $bundleCard->quantity,
                        'total_rejection' => $bundleCard->total_rejection ?? 0,
                        'print_rejection' => $bundleCard->print_rejection ?? 0,
                        'embroidary_rejection' => $bundleCard->embroidary_rejection ?? 0,
                        'buyer_name' => $bundleCard->buyer->name ?? '',
                        'style_name' => $bundleCard->order->style_name ?? '',
                        'po_no' => $bundleCard->purchaseOrder->po_no ?? '',
                        'color_name' => $bundleCard->color->name ?? '',
                        'lot_no' => $bundleCard->lot->lot_no ?? '',
                        'cutting_no' => $bundleCard->cutting_no ?? '',
                        'size_name' => $bundleCard->size->name ?? '',
                        'bundle_no' => $bundleCard->details->is_manual == 1 ? $bundleCard->size_wise_bundle_no : ($bundleCard->{getbundleCardSerial()} ?? $bundleCard->bundle_no),
                    ];
                });
        });
    }

    public function cuttingInventoryScan($order_id = null, $bundle_card_id = null)
    {
        $solidItem = 0;
        $challan_no = $this->getChallanNo($solidItem);
        $bundle_info = $this->getCuttingInventoryData($challan_no, $solidItem);

        return view('inputdroplets::forms.solid_inventory_scan', [
            'challan_no' => $challan_no,
            'bundle_info' => $bundle_info,
            'order_id' => $order_id,
            'bundle_card_id' => $bundle_card_id
        ]);
    }

    public function bundleReceivedRromPrint()
    {
        $embellishmentItem = 1;
        $challan_no = $this->getChallanNo($embellishmentItem);
        $bundle_info = $this->getCuttingInventoryData($challan_no, $embellishmentItem);

        return view('inputdroplets::forms.print_received_inventory_scan', [
            'challan_no' => $challan_no,
            'bundle_info' => $bundle_info
        ]);
    }

    public function bundleReceivedFromPrintPost(Request $request)
    {
        $responseData = (new PrintRcvScanAction)->setRequest($request)->handle();

        return response()->json($responseData);
    }


    public function cuttingInventoryScanPost(Request $request)
    {
        $responseData = (new SolidInputScanAction)->setRequest($request)->handle();

        return response()->json($responseData);
    }

    public function getSewingFloors()
    {
        return Floor::pluck('floor_no', 'id')->all();
    }

    public function createChallanForSewing($id)
    {
        $floors = $this->getSewingFloors();
        $challan_info = CuttingInventoryChallan::where('id', $id)->first();
        $redirectBackStatus = false;
        if (!$challan_info || $challan_info->type == 'challan') {
            Session::flash('error', 'Already exist this challan!');
            $redirectBackStatus = true;
        } elseif (!$challan_info->cutting_inventory->count()) {
            Session::flash('error', 'No bundles found in this challan!');
            $redirectBackStatus = true;
        }

        if ($redirectBackStatus) {
            return redirect('/view-tag-list');
        }

        return view('inputdroplets::forms.sewing_input_challan', [
            'floors' => $floors,
            'challan_info' => $challan_info
        ]);
    }

    public function createChallan($challan_no)
    {
        $alreadyExistChallan = CuttingInventoryChallan::where('challan_no', $challan_no)->count();
        if ($alreadyExistChallan) {
            Session::flash('error', 'Already exist this challan');
            return redirect()->back();
        }
        $floors = $this->getSewingFloors();
        $cutting_inventory = CuttingInventory::with('bundlecard:id,color_id')
            ->where('challan_no', $challan_no)
            ->first();

        return view('inputdroplets::forms.create_sewing_input_challan', [
            'floors' => $floors,
            'cutting_inventory' => $cutting_inventory
        ]);
    }

    public function createChallanTag($challan_no)
    {
        try {
            DB::beginTransaction();
            $alreadyExistChallan = CuttingInventoryChallan::where('challan_no', $challan_no)->count();
            if (!$alreadyExistChallan) {
                $cutting_inventory = CuttingInventory::with('bundlecard:id,color_id')
                    ->where('challan_no', $challan_no)
                    ->first();

                if ($cutting_inventory) {
                    $printStatus = $cutting_inventory->print_status != 0 ? 1 : 0;
                    $input = [
                        'challan_no' => $challan_no,
                        'status' => ACTIVE,
                        'created_by' => userId(),
                        'type' => TAG,
                        'color_id' => $cutting_inventory->bundlecard->color_id
                    ];
                    $challan = CuttingInventoryChallan::create($input);
                    $challan->cutting_inventory()->update([
                        'status' => ACTIVE
                    ]);
                    DB::commit();
                    (new PrintRcvInputCacheKeyService)->setItemStatus($printStatus)->removeCache();
                    return redirect('view-tag/' . $challan_no);
                }
            } else {
                Session::flash('error', 'Already exist this challan');
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    public function viewTag($challan_no)
    {
        $tagChallan = CuttingInventoryChallan::query()
            ->with([
                'cutting_inventory:id,challan_no,bundle_card_id',
                'cutting_inventory.bundlecard.details:id,is_manual',
                'cutting_inventory.bundlecard.buyer:id,name',
                'cutting_inventory.bundlecard.order:id,style_name',
                'cutting_inventory.bundlecard.purchaseOrder:id,po_no',
                'cutting_inventory.bundlecard.color:id,name',
                'cutting_inventory.bundlecard.size:id,name',
                'cutting_inventory.bundlecard.lot:id,lot_no'
            ])
            ->where('challan_no', $challan_no)
            ->first();

        return view('inputdroplets::pages.sewing_input_challan_tag', [
            'challan_no' => $challan_no,
            'tagChallan' => $tagChallan,
            'factory' => Factory::whereId(currentUser()->factory_id)->first()
        ]);
    }

    public function createChallanForLineInput(Request $request)
    {
        $request->validate([
            'line_id' => 'required',
            'floor_id' => 'required',
            'color_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $alreadyExistThisChallan = CuttingInventoryChallan::where('challan_no', $request->challan_no)->count();
            if (!$alreadyExistThisChallan) {

                $input = [
                    'challan_no' => $request->challan_no,
                    'status' => ACTIVE,
                    'line_id' => $request->line_id,
                    'created_by' => userId(),
                    'type' => CHALLAN,
                    'input_date' => $this->operation_date,
                    'color_id' => $request->color_id,
                ];
                $challan = CuttingInventoryChallan::create($input);
                $challan->cutting_inventory()->update([
                    'status' => ACTIVE
                ]);
                $bundleIds = $challan->cutting_inventory->pluck('bundle_card_id')->all();
                $printStatus = $challan->cutting_inventory->first()->print_status;

                DB::table('bundle_cards')
                    ->whereIn('id', $bundleIds)
                    ->update([
                        'input_date' => $this->operation_date
                    ]);
                DB::commit();
                (new PrintRcvInputCacheKeyService)->setItemStatus($printStatus)->removeCache();

                // Notify Users for Approval
                $notification = ['challan_no' => $request->challan_no, 'factory_id' => factoryId()];
                $this->notifyUser($notification);

                return redirect('view-challan/' . $challan->id);
            } else {
                Session::flash('error', 'Already exist this challan');
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }

        return redirect()->back();
    }

    private function notifyUser($notification)
    {
        // Notify Users for Approval
        $approvalUser = Approval::where('page_name', CuttingInventoryChallan::APPROVAL_PAGE_NAME)->orderBy('priority', 'ASC')->first();
        if ($approvalUser) {
            $userlist = collect([$approvalUser->user_id, $approvalUser->alternative_user_id])->unique()->filter();
            $users =  User::whereIn('id', $userlist)->get();
            Notification::sendNow($users, new CuttingInventoryNotification($notification));
        }
    }

    public function createChallanForSewingLine(Request $request)
    {
        $request->validate([
            'line_id' => 'required',
            'floor_id' => 'required',
        ], [
            'required' => "Required Field"
        ]);

        $redirectUrl = '/create-challan-for-sewing/' . $request->id;

        try {
            $challan = CuttingInventoryChallan::findOrFail($request->id);
            if ($challan->type == 'challan') {
                Session::flash('error', 'Already created this challan');
            } else {
                DB::beginTransaction();
                $bundleIds = $challan->cutting_inventory->pluck('bundle_card_id')->all();
                $challan->update([
                    'line_id' => $request->line_id,
                    'type' => CHALLAN,
                    'input_date' => $this->operation_date
                ]);
                DB::table('bundle_cards')
                    ->whereIn('id', $bundleIds)
                    ->update([
                        'input_date' => $this->operation_date
                    ]);
                DB::commit();
                // Notify Users for Approval
                $notification = ['challan_no' => $challan->challan_no, 'factory_id' => factoryId()];
                $this->notifyUser($notification);

                $redirectUrl = '/view-challan/' . $request->id;
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!! ' . $e->getMessage());
        }

        return redirect($redirectUrl);
    }

    public function getChallanFactory($factoryId)
    {
        return Factory::where('id', $factoryId)->first();
    }

    public function viewChallan($id)
    {
        try {
            $challan = CuttingInventoryChallan::with([
                'line:id,line_no,floor_id',
                'line.floor:id,floor_no',
                'color:id,name'
            ])->findOrFail($id);

            $inputBundles = BundleCard::with([
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder:id,po_no,po_quantity',
                'color:id,name',
                'size:id,name',
                'lot:id,lot_no',
                'garmentsItem:id,name'
            ])
                ->whereIn('id', $challan->cutting_inventory->pluck('bundle_card_id'))
                ->select(
                    'id',
                    'buyer_id',
                    'order_id',
                    'purchase_order_id',
                    'color_id',
                    'size_id',
                    'garments_item_id',
                    'cutting_no',
                    'lot_id',
                    'quantity',
                    'total_rejection',
                    'print_rejection',
                    'embroidary_rejection'
                )->get();

            $factory = $this->getChallanFactory($challan->factory_id);
            $order_ids = $inputBundles->unique('order_id')->pluck('order_id')->toArray();
            $poWiseSizeDetails = [];
            PoColorSizeBreakdown::query()
                ->whereIn('order_id', $order_ids)
                ->get()
                ->map(function ($item) use(&$poWiseSizeDetails) {
                    if (array_key_exists($item->purchase_order_id, $poWiseSizeDetails)) {
                        $poWiseSizeDetails[$item->purchase_order_id] = array_unique(array_merge($poWiseSizeDetails[$item->purchase_order_id], $item->sizes));
                    } else {
                        $poWiseSizeDetails[$item->purchase_order_id] = $item->sizes;
                    }
                });
            $cuttingNumbers = $inputBundles->pluck('cutting_no')->toArray();
            $cuttingNumbers = array_unique($cuttingNumbers);

            $lots = $inputBundles->pluck('lot.lot_no')->toArray();
            $lots = array_unique($lots);

            return view('inputdroplets::pages.view_input_challan', [
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

    public function viewTagWiseBundles($challan_no)
    {
        $challan_info = $this->getBundleList($challan_no, TAG);

        return view('inputdroplets::pages.input_challan_wise_bundles', [
            'challan_info' => $challan_info,
            'title' => 'Tag Challan'
        ]);
    }

    public function viewInputChallanWiseBundlesList($challan_no)
    {
        $challan_info = $this->getBundleList($challan_no, CHALLAN);

        return view('inputdroplets::pages.input_challan_wise_bundles', [
            'challan_info' => $challan_info,
            'title' => 'Input Challan'
        ]);
    }

    public function getBundleList($challan_no, $type)
    {
        return CuttingInventoryChallan::with([
            'line:id,line_no,floor_id',
            'line.floor:id,floor_no',
            'cutting_inventory:id,bundle_card_id,challan_no',
            'cutting_inventory.bundlecard:id,bundle_no,size_wise_bundle_no,bundle_card_generation_detail_id,suffix,serial,cutting_no,buyer_id,order_id,purchase_order_id,color_id,size_id,lot_id,quantity,total_rejection,print_rejection,embroidary_rejection,sewing_output_date',
            'cutting_inventory.bundlecard.details:id,is_manual',
            'cutting_inventory.bundlecard.buyer:id,name',
            'cutting_inventory.bundlecard.order:id,style_name',
            'cutting_inventory.bundlecard.purchaseOrder:id,po_no,po_quantity',
            'cutting_inventory.bundlecard.color:id,name',
            'cutting_inventory.bundlecard.size:id,name',
            'cutting_inventory.bundlecard.lot:id,lot_no'
        ])
            ->where(['challan_no' => $challan_no, 'type' => $type])
            ->first();
    }

    public function viewInputChallanWiseDeletedBundlesList($challan_no)
    {
        $challan_info = DB::table('cutting_inventories')
            ->leftJoin('cutting_inventory_challans', 'cutting_inventory_challans.challan_no', 'cutting_inventories.challan_no')
            ->leftJoin('users', 'users.id', 'cutting_inventories.deleted_by')
            ->leftJoin('bundle_cards', 'bundle_cards.id', 'cutting_inventories.bundle_card_id')
            ->leftJoin('bundle_card_generation_details', 'bundle_card_generation_details.id', 'bundle_cards.bundle_card_generation_detail_id')
            ->leftJoin('lines', 'lines.id', 'cutting_inventory_challans.line_id')
            ->leftJoin('floors', 'floors.id', 'lines.floor_id')
            ->leftJoin('buyers', 'buyers.id', 'bundle_cards.buyer_id')
            ->leftJoin('orders', 'orders.id', 'bundle_cards.order_id')
            ->leftJoin('purchase_orders', 'purchase_orders.id', 'bundle_cards.purchase_order_id')
            ->leftJoin('colors', 'colors.id', 'bundle_cards.color_id')
            ->leftJoin('sizes', 'sizes.id', 'bundle_cards.size_id')
            ->leftJoin('lots', 'lots.id', 'bundle_cards.lot_id')
            ->selectRaw(
                'lines.line_no, floors.floor_no, cutting_inventories.challan_no,bundle_cards.id, bundle_card_generation_details.is_manual,
            bundle_cards.bundle_no,bundle_cards.size_wise_bundle_no,bundle_cards.suffix,bundle_cards.serial,bundle_cards.cutting_no,bundle_cards.quantity,bundle_cards.total_rejection,bundle_cards.print_rejection,bundle_cards.embroidary_rejection,bundle_cards.sewing_output_date,
            buyers.name as buyer_name,
            orders.booking_no,orders.style_name,
            purchase_orders.po_no,purchase_orders.po_quantity,
            colors.name as color_name,
            sizes.name as size_name,
            lots.lot_no,
            cutting_inventories.deleted_at,
            CONCAT(users.first_name," ",users.last_name) as deleted_by'
            )
            ->where([
                'cutting_inventories.challan_no' => $challan_no,
                'cutting_inventory_challans.type' => CHALLAN
            ])
            ->whereNotNull('cutting_inventories.deleted_at')
            ->get();
        $challan_alternate_info = CuttingInventoryChallan::with([
            'line:id,line_no,floor_id',
            'line.floor:id,floor_no',
        ])
            ->where(['challan_no' => $challan_no, 'type' => CHALLAN])
            ->first();

        return view('inputdroplets::pages.input_challan_wise_deleted_bundles', [
            'challan_info' => $challan_info,
            'challan_alternate_info' => $challan_alternate_info,
            'title' => 'Input Challan Deleted Bundles'
        ]);
    }
    public function deleteInputBundle($bundle_card_id)
    {
        try {
            DB::beginTransaction();
            $cutting_inventory = CuttingInventory::where('bundle_card_id', $bundle_card_id);
            $alreadyExistInSewingoutputs = Sewingoutput::where('bundle_card_id', $bundle_card_id)
                ->count();

            if (!$alreadyExistInSewingoutputs) {
                if (CuttingInventory::find($cutting_inventory->first()->id)->delete()) {
                    DB::table('bundle_cards')
                        ->where('id', $bundle_card_id)
                        ->update([
                            'input_date' => null,
                            'print_embr_received_scan_time' => null,
                            'input_scan_time' => null,
                        ]);
                    $status = SUCCESS;
                    DB::commit();
                } else {
                    DB::rollBack();
                    $status = FAIL;
                }
            } else {
                $status = 403; // already exist in sewing output
            }
        } catch (Exception $e) {
            $status = FAIL;
        }

        return $status;
    }

    public function getTagOrChallanList($type)
    {
        return CuttingInventoryChallan::with([
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

        return view('inputdroplets::pages.view_challan_tag_list', [
            'title' => 'Challan List',
            'challan_list' => $challan_list
        ]);
    }

    public function editChallan($id)
    {
        $floors = $this->getSewingFloors();
        $challan_info = CuttingInventoryChallan::findOrFail($id);
        $lines = Line::where('floor_id', $challan_info->line->floor_id)
            ->pluck('line_no', 'id')
            ->all();

        return view('inputdroplets::forms.edit_sewing_input_challan', [
            'floors' => $floors,
            'lines' => $lines,
            'challan_info' => $challan_info
        ]);
    }

    public function updateChallan(Request $request)
    {
        try {
            DB::beginTransaction();
            $challan = CuttingInventoryChallan::with('cutting_inventory')
                ->findOrFail($request->challan_no);

            $bundleCardIds = $challan->cutting_inventory->pluck('bundle_card_id')->all();
            $sewingOutputs = Sewingoutput::whereIn('bundle_card_id', $bundleCardIds)->count();
            if ($sewingOutputs) {
                Session::flash('error', 'Cannot change Line because already send to Sewing Output');
                return redirect('view-challan-list');
            }
            $challan->update(['line_id' => $request->line_id]);
            DB::commit();

            Session::flash('success', S_UPDATE_MSG);
            return redirect('view-challan-list');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect('view-challan-list');
        }
    }

    public function viewTagList()
    {
        $tag_list = $this->getTagOrChallanList(TAG);
        return view('inputdroplets::pages.view_challan_tag_list', [
            'title' => 'Tag List',
            'tag_list' => $tag_list
        ]);
    }

    public function destroy($id)
    {
        try {

            DB::beginTransaction();
            $challan = CuttingInventoryChallan::with('cutting_inventory')->where('id', $id)->first();
            $bundleCardIds = $challan->cutting_inventory->pluck('bundle_card_id');
            $alreadyExistInSewingoutputs = Sewingoutput::whereIn('bundle_card_id', $bundleCardIds)->count();

            // if at least one bundle already have in sewingoutput, you cant delete
            if ($alreadyExistInSewingoutputs == 0) {
                DB::table('bundle_cards')
                    ->whereIn('id', $bundleCardIds)
                    ->update([
                        'input_date' => null,
                        'print_embr_received_scan_time' => null,
                        'input_scan_time' => null
                    ]);

                // $challan->delete(); // not working; confused
                CuttingInventoryChallan::where('id', $id)
                    ->first()
                    ->delete();

                DB::commit();
                Session::flash('success', 'Deleted Successfully');
            } else {
                Session::flash('error', 'Sorry!! Already exist in sewingoutput so you cant delete this challan');
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Not Successfully Deleted');
            DB::rollBack();
        }

        return redirect()->back();
    }

    public function challanWiseBundles()
    {
        return view('inputdroplets::pages.challan_wise_bundles');
    }

    public function challanWiseBundlesList($challan_no)
    {
        $challan_wise_bundles = CuttingInventoryChallan::with([
            'line:id,line_no,floor_id',
            'line.floor:id,floor_no',
            'cutting_inventory:id,bundle_card_id,challan_no,print_status',
            'cutting_inventory.sewingoutput:id,bundle_card_id',
            'cutting_inventory.bundlecard:id,size_wise_bundle_no,bundle_no,quantity,total_rejection,print_rejection,embroidary_rejection,sewing_rejection,suffix,cutting_no,buyer_id,order_id,purchase_order_id,color_id,size_id,lot_id',
            'cutting_inventory.bundlecard.buyer:id,name',
            'cutting_inventory.bundlecard.order:id,style_name',
            'cutting_inventory.bundlecard.purchaseOrder:id,po_no,po_quantity',
            'cutting_inventory.bundlecard.color:id,name',
            'cutting_inventory.bundlecard.size:id,name',
            'cutting_inventory.bundlecard.lot:id,lot_no',
        ])
            ->where(['challan_no' => $challan_no, 'type' => CHALLAN])
            ->first();

        $view = view('inputdroplets::pages.challan_wise_bundles_result', compact('challan_wise_bundles'))->render();
        return response()->json(['view' => $view, 'challan_wise_bundles' => $challan_wise_bundles]);
    }

    public function searchChallanOrTag(Request $request)
    {
        $challan_list = [];
        switch ($request->type) {
            case 'challan':
                if (!isset($request->q)) {
                    return redirect('view-challan-list');
                }

                $challan_list = CuttingInventoryChallan::withoutGlobalScope('factoryId')->with([
                    'line:id,line_no,floor_id',
                    'line.floor:id,floor_no',
                    'user:id,first_name,last_name,email,screen_name',
                    'user.factory:id,factory_name'
                ])
                    ->join('lines', 'lines.id', 'cutting_inventory_challans.line_id')
                    ->join('floors', 'floors.id', 'lines.floor_id')
                    ->join('users', 'users.id', 'cutting_inventory_challans.created_by')
                    ->where('cutting_inventory_challans.type', $request->type)
                    ->where('cutting_inventory_challans.factory_id', factoryId())
                    ->where('cutting_inventory_challans.challan_no', 'like', '%' . $request->q . '%')
                    ->orWhere('lines.line_no', 'like', '%' . $request->q . '%')
                    ->orWhere('floors.floor_no', 'like', '%' . $request->q . '%')
                    //->orWhere('users.first_name', 'like', '%' . $request->q . '%')
                    //->orWhere('users.last_name', 'like', '%' . $request->q . '%')
                    ->orderBy('cutting_inventory_challans.created_at', 'desc')
                    ->select('cutting_inventory_challans.*', 'lines.line_no as line_no', 'floors.floor_no as floor_no', 'users.first_name', 'users.last_name')
                    ->paginate();
                break;
            case 'tag':
                if (!isset($request->q)) {
                    return redirect('view-tag-list');
                }
                $challan_list = CuttingInventoryChallan::withoutGlobalScope('factoryId')
                    ->join('users', 'users.id', 'cutting_inventory_challans.created_by')
                    ->where('cutting_inventory_challans.type', $request->type)
                    ->where('cutting_inventory_challans.factory_id', factoryId())
                    ->where('cutting_inventory_challans.challan_no', 'like', '%' . $request->q . '%')
                    //->orWhere('users.first_name', 'like', '%' . $request->q . '%')
                    //->orWhere('users.last_name', 'like', '%' . $request->q . '%')
                    ->orderBy('cutting_inventory_challans.created_at', 'desc')
                    ->select('cutting_inventory_challans.*', 'users.first_name', 'users.last_name')
                    ->paginate();
                break;
        }
        return view('inputdroplets::pages.view_challan_tag_list', [
            'title' => $request->type == 'challan' ? 'Challan List' : 'Tag List',
            $request->type == 'challan' ? 'challan_list' : 'tag_list' => $challan_list,
            'q' => $request->q
        ]);
    }

    public function addBundleToTag()
    {
        $tagBundles = CuttingInventoryChallan::query()
            ->with([
                'cutting_inventory:id,bundle_card_id,challan_no',
                'cutting_inventory.bundlecard',
                'cutting_inventory.bundlecard.details:id,is_manual'
            ])
            ->where([
                'challan_no' => request()->get('tag-no'),
                'type' => TAG
            ])
            ->first();

        if ($tagBundles && $tagBundles->cutting_inventory->count()) {
            return view('inputdroplets::forms.add_bundle_to_tag')->with('tagBundles', $tagBundles);
        }

        Session::flash('error', "No bundles found in this tag!");
        return redirect()->back();
    }

    public function addBundleToTagPost(Request $request)
    {
        $responseData = (new InputTagScanAction)->setRequest($request)->handle();

        return response()->json($responseData);
    }


    public function viewBinCard($id)
    {
        try {

            $challan = CuttingInventoryChallan::query()
                ->with([
                    'color:id,name'
                ])
                ->findOrFail($id);

            $inputBundles = BundleCard::query()
                ->with([
                    'buyer:id,name',
                    'order:id,style_name',
                    'color:id,name',
                ])
                ->whereIn('id', $challan->cutting_inventory->pluck('bundle_card_id'))
                ->select(
                    'id',
                    'buyer_id',
                    'order_id',
                    'color_id',
                )->get();

            $factoryId = factoryId();
            $buyerId = ($inputBundles->unique('order_id')[0])->buyer_id;
            $jobNo = null;
            $poNo = [];
            $fromDate = null;
            $toDate = null;
            $styleName = null;
            $searchType = null;
            $dealingMerchantId = null;
            $orderReportDTO = new OrderReportDTO();
            $orderReportDTO->setFactoryId($factoryId);
            $orderReportDTO->setBuyerId($buyerId);
            $orderReportDTO->setJobNo($jobNo);
            $orderReportDTO->setPoNo($poNo);
            $orderReportDTO->setStyleName($styleName);
            $orderReportDTO->setFromDate($fromDate);
            $orderReportDTO->setToDate($toDate);
            $orderReportDTO->setSearchType($searchType);
            $orderReportDTO->setDealingMerchantId($dealingMerchantId);
            $orderData = OrderReportService::reportData($orderReportDTO);
            $product_image = collect($orderData['pos'])->pluck('order.images')->whereNotNull()->first();
            return view('inputdroplets::pages.view_bin_card', [
                'challan' => $challan,
                'inputBundles' => $inputBundles,
                'product_images' => $product_image
            ]);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateChallanRibDetails($id, Request $request): JsonResponse
    {
        $request->validate([
            "total_rib_size" => 'required',
        ]);
        $challan = CuttingInventoryChallan::query()->findOrFail($id);
        $challan->fill($request->all())->save();

        return response()->json(["message" => "Successfully data stored"], Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function viewChallanRibDetails($id): JsonResponse
    {
        return response()->json(CuttingInventoryChallan::query()
            ->select('total_rib_size', 'rib_comments')
            ->findOrFail($id));
    }
}
