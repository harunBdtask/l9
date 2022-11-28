<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use Session;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationCache;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class ChallanController extends Controller
{
    public function getChallanWiseBundle(Request $request)
    {
        $bundles = BundleCard::with([
            'cutting_inventory:id,bundle_card_id,challan_no',
            'print_inventory:id,bundle_card_id,challan_no,status',
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no',
            'color:id,name',
            'size:id,name',
            'lot:id,lot_no'
        ])
            ->where('bundle_card_generation_detail_id', $request->sid)
            ->get();

        return view('cuttingdroplets::forms.challan_wise_bundle', [
            'bundles' => $bundles,
            'sid' => $request->sid
        ]);
    }

    public function updateCuttingProduction()
    {
        $challan = request()->get('challan') ?? '';
        $bundles = BundleCard::with([
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no,po_quantity',
            'color:id,name',
            'size:id,name',
            'lot:id,lot_no'
        ])
            ->where('bundle_card_generation_detail_id', $challan)
            ->get();

        return view('cuttingdroplets::forms.update_cutting_production', [
            'bundles' => $bundles,
            'challan' => $challan
        ]);
    }

    public function deleteCuttingBundle($bundle_id)
    {
        try {
            $status = FAIL;
            $bundle = BundleCard::with([
                'cutting_inventory:id,bundle_card_id',
                'print_inventory:id,bundle_card_id'
            ])->findOrFail($bundle_id);

            if ($bundle->cutting_inventory) {
                $message = "You can't delete this bundle because already scanned in input section";
            } elseif ($bundle->print_inventory) {
                $message = "You can't delete this bundle because already scanned in print section";
            } else {
                $exception = DB::transaction(function () use ($bundle) {
                    $bundle->delete();
                });
                //$this->updateBundleCardGenerationCache($bundle_card_generation_detail_id);
                if (\is_null($exception)) {
                    $status = SUCCESS;
                    $message = S_DELETE_MSG;
                } else {
                    $status = FAIL;
                    $message = \SOMETHING_WENT_WRONG;
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    public function updateBundle(Request $request)
    {
        $status = FAIL;
        $bundle = BundleCard::findOrFail($request->id);
        $bundle->quantity = ($request->qty ?: 0);
        $bundle->created_at = $request->cutting_date;
        if ($bundle->save()) {
            $status = SUCCESS;
        }
        return $status;
    }

    private function updateBundleCardGenerationCache($id)
    {
        $with = [
            'bundleCards:id,buyer_id,order_id,color_id,size_id,purchase_order_id,country_id,lot_id,status,roll_no,suffix,serial,cutting_no,bundle_no,size_wise_bundle_no,quantity,bundle_card_generation_detail_id,sl_overflow',
            'bundleCards.lot:id,lot_no',
            'bundleCards.color:id,name',
            'bundleCards.order:id,style_name',
            'bundleCards.purchaseOrder:id,po_no,po_quantity',
            'bundleCards.size:id,name',
            'buyer:id,name',
            'order:id,style_name',
            'factory:id,factory_name,factory_address',
            'garmentsItem:id,name',
            'part:id,name',
            'type:id,name',
            'cuttingFloor:id,floor_no',
            'cuttingTable:id,table_no'
        ];
        $bundleCardGenerationDetail = BundleCardGenerationDetail::with($with)->findOrFail($id);
        $bundleCards = $bundleCardGenerationDetail->bundleCards->sortBy('bundle_no')->values();
        if ($bundleCardGenerationDetail->is_manual) {
            $stickers = $bundleCards->groupBy('color_id')->map(function ($item, $key) {
                $sticker_sorting = 0;
                $bundleCards = $item->map(function ($item, $key) {
                    $item->size_suffix = $item->suffix ? $item->size->name . ' (' . $item->suffix . ')' : $item->size->name;

                    return $item;
                })->groupBy('size_suffix')->map(function ($item, $key) use (&$sticker_sorting) {
                    return [
                        'sl_start' => explode('-', $item->first()->serial)[0],
                        'sl_end' => explode('-', $item->last()->serial)[1],
                        'color' => $item->first()->color->name,
                        'cutting_no' => $item->first()->cutting_no,
                        'bundle_no' => $item->first()->bundle_no,
                        'bundles' => $item->count(),
                        'quantity' => $item->sum('quantity'),
                        'sorting' => $sticker_sorting++
                    ];
                });

                return $bundleCards;
            });
            $bundleCardGenerationDetails = $bundleCardGenerationDetail->toArray();
            $bundleCardGenerationDetails['order']['pq_qty_sum'] = $bundleCardGenerationDetail->order->pq_qty_sum;
        } else {
            $stickers = $bundleCards->groupBy('color_id')->map(function ($item, $key) {
                $bundleCards = $item->map(function ($item, $key) {
                    $item->size_suffix = $item->suffix ? $item->size->name . ' (' . $item->suffix . ')' : $item->size->name;

                    return $item;
                })->groupBy('size_suffix')->map(function ($item, $key) {
                    return [
                        'sl_start' => explode('-', $item->first()->serial)[0],
                        'sl_end' => explode('-', $item->last()->serial)[1],
                        'color' => $item->first()->color->name,
                        'cutting_no' => $item->first()->cutting_no,
                    ];
                });

                return $bundleCards;
            });
            $bundleCardGenerationDetails = $bundleCardGenerationDetail->toArray();
            $bundleCardGenerationDetails['roll_summary'] = $bundleCardGenerationDetail->roll_summary;
            $bundleCardGenerationDetails['marker_piece'] = $bundleCardGenerationDetail->marker_piece;
            $bundleCardGenerationDetails['bundle_summary'] = $bundleCardGenerationDetail->bundle_summary;
            $bundleCardGenerationDetails['allColors'] = $bundleCardGenerationDetail->all_colors;
            $bundleCardGenerationDetails['total_cutting_quantity_should_be'] = $bundleCardGenerationDetail->total_cutting_quantity_should_be;
            $bundleCardGenerationDetails['quantity_save_or_loss'] = $bundleCardGenerationDetail->quantity_save_or_loss;
            $bundleCardGenerationDetails['used_consumption'] = $bundleCardGenerationDetail->used_consumption;
            $bundleCardGenerationDetails['consumption_save_or_loss'] = $bundleCardGenerationDetail->consumption_save_or_loss;
            $bundleCardGenerationDetails['fabric_save'] = $bundleCardGenerationDetail->fabric_save;
            $bundleCardGenerationDetails['result'] = $bundleCardGenerationDetail->result;
            $bundleCardGenerationDetails['order']['pq_qty_sum'] = $bundleCardGenerationDetail->order->pq_qty_sum;
        }

        $details = [
            'bundleCardGenerationDetail' => $bundleCardGenerationDetails,
            'bundleCards' => $bundleCards,
            'stickers' => $stickers
        ];
        if (BundleCardGenerationCache::where('sid', $bundleCardGenerationDetail->id)->count()) {
            BundleCardGenerationCache::where('sid', $bundleCardGenerationDetail->id)
                ->update([
                    'details' => $details
                ]);
        }
        $bundleCardGenerationCache = BundleCardGenerationCache::firstOrNew([
            'bg_id' => $bundleCardGenerationDetail->id,
        ]);
        $bundleCardGenerationCache->sid = $bundleCardGenerationDetail->sid;
        $bundleCardGenerationCache->details = $details;


        if ($bundleCardGenerationCache->save()) {
            return true;
        }
    }
}
