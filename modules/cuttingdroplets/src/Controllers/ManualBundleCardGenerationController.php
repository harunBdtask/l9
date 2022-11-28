<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Requests\BundleCardRegenerateRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Requests\ManualBundleCardGenRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationView;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\POQtyValidateService;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;
use SkylarkSoft\GoRMG\SystemSettings\Models\Type;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Actions\UpdateCuttingQtyInReportsAction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationCache;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class ManualBundleCardGenerationController extends Controller
{
    public function index()
    {
        $bundleCardDetails = BundleCardGenerationView::getBundleGenerationListQuery(1)->paginate();

        return view('cuttingdroplets::pages.bundle_card_generation_list_manual', ['bundleCardDetails' => $bundleCardDetails]);
    }

    public function create()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        $cuttingFloors = CuttingFloor::pluck('floor_no', 'id')->all();
        $countries = Country::pluck('name', 'id')->all();
        $parts = Part::pluck('name', 'id')->all();
        $types = Type::withoutGlobalScope('factoryId')->pluck('name', 'id')->all();
        $suffix = getBundleCardSuffixOptions();
        $garmentsProductionSettings = GarmentsProductionEntry::query()->where('factory_id', factoryId())->first();
        $sizeSuffixSerialEnabled = $garmentsProductionSettings->size_suffix_sl_status ?? 0;

        return view('cuttingdroplets::forms.bundle_card_generation_form_manual', [
            'bundleCard' => null,
            'cuttingFloors' => $cuttingFloors,
            'buyers' => $buyers,
            'countries' => $countries,
            'parts' => $parts,
            'types' => $types,
            'suffix' => $suffix,
            'sizeSuffixSerialEnabled' => $sizeSuffixSerialEnabled,
        ]);
    }

    public function store(ManualBundleCardGenRequest $request)
    {
        DB::transaction(function () use ($request) {
            $colorIds = Lot::whereIn('id', $request->get('lot'))->pluck('color_id')->all();

            $colors = [];
            foreach ($colorIds as $colorId) {
                $colors = [
                    'color_id' => $colorId
                ];
            }

            $cuttingNos = $request->get('cutting_nos');
            $cutting = '';
            foreach ($cuttingNos as $colorId => $cuttingNo) {
                $cutting .= $colorId . ': ' . $cuttingNo . '; ';
            }

            $cutting = rtrim($cutting, '; ');

            $genDetails = $request->only([
                'booking_consumption',
                'booking_dia',
                'buyer_id',
                'order_id',
                'garments_item_id',
                'country_id',
                'cutting_floor_id',
                'cutting_table_id',
                'is_tube',
                'part_id',
                'type_id',
            ]);
            $genDetails['is_manual'] = 1;
            $genDetails['cutting_no'] = $cutting;
            $genDetails['colors'] = json_encode($colors);

            $bundleCardGenerationDetail = BundleCardGenerationDetail::create(array_filter($genDetails));
            $bundleCardGenerationDetail->sid = $bundleCardGenerationDetail->id;
            $bundleCardGenerationDetail->save();

            $bundleInfo = $request->only([
                'bundle_no',
                'size_wise_bundle_no',
                'roll_no',
                'quantity',
                'sl_start',
                'sl_end',
                'size',
                'suffix',
                'lot',
                'purchase_order_id',
                'country_id'
            ]);
            $bundleCards = $this->makeBundles($bundleCardGenerationDetail, $bundleInfo);

            BundleCard::insert($bundleCards);
            // $this->updateBundleCardGenerationCache($bundleCardGenerationDetail->id);
        });

        return redirect('bundle-card-generation-manual');
    }

    private function updateBundleCardGenerationCache($id)
    {
        $with = [
            'bundleCards:id,buyer_id,order_id,color_id,size_id,purchase_order_id,country_id,lot_id,status,roll_no,suffix,serial,cutting_no,bundle_no,size_wise_bundle_no,quantity,bundle_card_generation_detail_id,sl_overflow',
            'bundleCards.lot:id,lot_no',
            'bundleCards.color:id,name',
            'bundleCards.order:id,style_name,reference_no',
            'bundleCards.purchaseOrder:id,po_no,po_quantity',
            'bundleCards.size:id,name',
            'buyer:id,name',
            'order:id,style_name,reference_no',
            'factory:id,factory_name,factory_address',
            'part:id,name',
            'type:id,name',
            'garmentsItem:id,name',
            'cuttingFloor:id,floor_no',
            'cuttingTable:id,table_no'
        ];
        $bundleCardGenerationDetail = BundleCardGenerationDetail::with($with)->findOrFail($id);
        $bundleCards = $bundleCardGenerationDetail->bundleCards->sortBy('bundle_no')->values();
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
        $details = [
            'bundleCardGenerationDetail' => $bundleCardGenerationDetails,
            'bundleCards' => $bundleCards,
            'stickers' => $stickers
        ];
        $bundleCardGenerationCache = BundleCardGenerationCache::firstOrNew([
            'bg_id' => $bundleCardGenerationDetail->id,
        ]);
        $bundleCardGenerationCache->sid = $bundleCardGenerationDetail->sid;
        $bundleCardGenerationCache->details = $details;

        if ($bundleCardGenerationCache->save()) {
            return true;
        }
    }

    public function makeBundles(BundleCardGenerationDetail $genDetail, $bundleInfo = [])
    {
        $data = [];
        /*$order = PurchaseOrder::findOrFail($bundleInfo['purchase_order_id']);*/
        $country = PurchaseOrder::find($bundleInfo['country_id']);

        foreach ($bundleInfo['bundle_no'] as $key => $bundleNo) {
            $lot = Lot::findOrFail($bundleInfo['lot'][$key]);
            $size = Size::findOrFail($bundleInfo['size'][$key]);

            $data[] = [
                'bundle_no' => $bundleNo,
                'size_wise_bundle_no' => $bundleInfo['size_wise_bundle_no'][$key] ?? $bundleNo,
                'roll_no' => $bundleInfo['roll_no'][$key],
                'quantity' => $bundleInfo['quantity'][$key],
                'buyer_id' => $genDetail->buyer_id,
                'order_id' => $genDetail->order_id,
                'garments_item_id' => $genDetail->garments_item_id,
                'lot_id' => $bundleInfo['lot'][$key],
                'color_id' => $lot->color_id,
                'size_id' => $bundleInfo['size'][$key],
                'suffix' => $bundleInfo['suffix'][$key],
                'serial' => $bundleInfo['sl_start'][$key] . '-' . $bundleInfo['sl_end'][$key],
                'bundle_card_generation_detail_id' => $genDetail->id,
                'factory_id' => \Auth::user()->factory_id,
                'status' => 0,
                'cutting_challan_status' => 0,
                'cutting_qc_challan_status' => 0,
                'purchase_order_id' => $bundleInfo['purchase_order_id'][$key],
                'country_id' => $bundleInfo['country_id'],
                'cutting_table_id' => $genDetail->cutting_table_id,
                'cutting_no' => $this->findCuttingNo($genDetail->cutting_no, $lot->color_id),
                'cutting_floor_id' => $genDetail->cutting_floor_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        return $data;
    }

    private function findCuttingNo($cuttingNos, $colorId)
    {
        $cuttingNosWithColor = explode('; ', $cuttingNos);
        $cuttingNos = [];

        foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
            $cutting = explode(': ', $cuttingNoWithColor);

            if (count($cutting) == 2) {
                $cuttingNos[$cutting[0]] = $cutting[1];
            }
        }

        if (array_key_exists($colorId, $cuttingNos)) {
            return $cuttingNos[$colorId];
        }

        return 1;
    }

    public function show($id)
    {
        if (BundleCardGenerationCache::where('bg_id', $id)->count()) {
            $cacheData = BundleCardGenerationCache::where('bg_id', $id)->first()->details;
            $data = [
                'bundleCardGenerationDetail' => $cacheData['bundleCardGenerationDetail'],
                'bundleCards' => collect($cacheData['bundleCards']),
                'stickers' => collect($cacheData['stickers'])
            ];
        } else {
            $bundleCardGenerationDetail = BundleCardGenerationDetail::with([
                'bundleCards:id,buyer_id,order_id,color_id,size_id,purchase_order_id,country_id,lot_id,status,roll_no,suffix,serial,cutting_no,bundle_no,size_wise_bundle_no,quantity,bundle_card_generation_detail_id',
                'bundleCards.lot:id,lot_no',
                'bundleCards.color:id,name',
                'order:id,style_name,reference_no',
                'bundleCards.purchaseOrder:id,po_no',
                'bundleCards.size:id,name',
                'factory:id,factory_name',
                'part:id,name',
                'type:id,name',
                'cuttingTable:id,floor_no',
                'cuttingTable:id,table_no'
            ])
            ->withSum('purchaseOrderQtys as order_quantity', 'po_quantity')
            ->findOrFail($id);

            $bundleCards = $bundleCardGenerationDetail->bundleCards->sortBy('bundle_no')->values();
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
                        'bundle_no' => $item->first()->bundle_no,
                        'bundles' => $item->count(),
                        'quantity' => $item->sum('quantity')
                    ];
                });

                return $bundleCards;
            });
            $data = [
                'bundleCardGenerationDetail' => $bundleCardGenerationDetail,
                'bundleCards' => $bundleCards,
                'stickers' => $stickers
            ];
        }

        return view('cuttingdroplets::pages.bundle_card_generation_details_manual', $data);
    }

    public function reGenerateForm($id)
    {
        $parts = Part::pluck('name', 'id')->all();
        $types = Type::withoutGlobalScope('factoryId')->pluck('name', 'id')->all();

        return view('cuttingdroplets::forms.bundle_card_re_generation_form_manual', [
            'parts' => $parts,
            'types' => $types,
        ]);
    }

    public function reGenerate($id, BundleCardRegenerateRequest $request)
    {
        $genDetail = BundleCardGenerationDetail::findOrFail($id);
        $newGenDetail = $genDetail->replicate();

        DB::transaction(function () use ($request, $newGenDetail) {
            $newGenDetail->part_id = $request->get('part_id');
            $newGenDetail->type_id = $request->get('type_id');
            $newGenDetail->is_regenerated = 1;
            $newGenDetail->save();

            // $this->updateBundleCardGenerationCache($newGenDetail->id);
        });

        return redirect('bundle-card-generation-manual');
    }

    public function print($id)
    {
        if (BundleCardGenerationCache::where('bg_id', $id)->count()) {
            $cacheData = BundleCardGenerationCache::where('bg_id', $id)->first()->details;
            $data = [
                'bundleCardGenerationDetail' => $cacheData['bundleCardGenerationDetail'],
                'bundleCards' => collect($cacheData['bundleCards']),
                'stickers' => collect($cacheData['stickers'])
            ];
        } else {
            $bundleCardGenerationDetail = BundleCardGenerationDetail::with([
                'bundleCards:id,buyer_id,order_id,color_id,size_id,purchase_order_id,country_id,lot_id,status,roll_no,suffix,serial,cutting_no,bundle_no,size_wise_bundle_no,quantity,bundle_card_generation_detail_id',
                'bundleCards.lot:id,lot_no',
                'bundleCards.color:id,name',
                'order:id,style_name,reference_no',
                'bundleCards.purchaseOrder:id,po_no',
                'bundleCards.size:id,name',
                'factory:id,factory_name',
                'part:id,name',
                'type:id,name',
                'cuttingTable:id,floor_no',
                'cuttingTable:id,table_no'
            ])
            ->withSum('purchaseOrderQtys as order_quantity', 'po_quantity')
            ->findOrFail($id);

            $bundleCards = $bundleCardGenerationDetail->bundleCards->sortBy('bundle_no')->values();

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
                        'bundle_no' => $item->first()->bundle_no,
                        'bundles' => $item->count(),
                        'quantity' => $item->sum('quantity')
                    ];
                });

                return $bundleCards;
            });

            $data = [
                'bundleCardGenerationDetail' => $bundleCardGenerationDetail,
                'bundleCards' => $bundleCards,
                'stickers' => $stickers
            ];
        }

        if (getBundleCardPrintStyle() == 1) {
            $view = view('cuttingdroplets::pages.print_bundlecards_manual_barcode', $data);
        } else {
            $view = view('cuttingdroplets::pages.print_bundlecards_manual', $data);
        }

        return $view;
    }

    public function destroy($id)
    {
        $bundleCards = BundleCard::where([
            'bundle_card_generation_detail_id' => $id,
            'status' => 1
        ])->pluck('id')->all();

        $printInventories = PrintInventory::whereIn('bundle_card_id', $bundleCards)->count();
        $cuttingInventories = CuttingInventory::whereIn('bundle_card_id', $bundleCards)->count();

        if ($printInventories) {
            Session::flash('failure', 'Sorry!! Cannot delete because already sent to print');
            return redirect()->back();
        } elseif ($cuttingInventories) {
            Session::flash('failure', 'Sorry!! Cannot delete because already sent to input/tag');
            return redirect()->back();
        }

        $generationDetail = BundleCardGenerationDetail::findOrFail($id);
        $isDeleted = DB::transaction(function () use ($generationDetail) {
            $count = BundleCardGenerationDetail::where('sid', $generationDetail->sid)->count();

            if ($count == 1) {
                $generationDetail->delete();
                $generationDetail->bundleCards()->delete();
                return true;
            }

            if ($generationDetail->id != $generationDetail->sid) {
                $generationDetail->delete();

                return true;
            }

            return false;
        });

        return $isDeleted ? back()->with('success', 'Successfully deleted.') : back()->with('failure', "Can't be deleted. Beacuse there are bundle cards associated with it.");
    }

    /*public function destroy($id)
    {
        $generationDetail = BundleCardGenerationDetail::findOrFail($id);

        $isDeleted = \DB::transaction(function() use($generationDetail) {
            $count = BundleCardGenerationDetail::where('sid', $generationDetail->sid)->count();

            if ($count == 1) {
                $generationDetail->bundleCards()->delete();
                $generationDetail->delete();

                return true;
            }

            if ($generationDetail->id != $generationDetail->sid) {
                $generationDetail->delete();

                return true;
            }

            return false;
        });

        return $isDeleted ? back()->with('success', 'Successfully deleted.') : back()->with('failure', "Can't be deleted. Beacuse there are bundle cards associated with it.");
    }*/

    public function scanBundleCards($id)
    {
        $bundleCardGenerationDetail = BundleCardGenerationDetail::with('bundleCards')->findOrFail($id);
//        $qtyExceeds = (new POQtyValidateService())->isMaxPOQtyExceeds($bundleCardGenerationDetail);

        // if ($qtyExceeds) {
        //     Session::flash('failure', 'Max po qty exceeds!');
        //     return redirect()->back();
        // }

        DB::transaction(function () use ($bundleCardGenerationDetail) {
            $bundleCards = $bundleCardGenerationDetail->bundleCards;

            foreach ($bundleCards as $bundleCard) {
                $bundleCard->status = 1;
                $bundleCard->cutting_qc_challan_no = $bundleCardGenerationDetail->id;
                $bundleCard->cutting_challan_no = $bundleCardGenerationDetail->id;
                $bundleCard->cutting_challan_status = 1;
                $bundleCard->cutting_qc_challan_status = 0;
                $cutting_date = operationDate();
                $bundleCard->cutting_date = $cutting_date;
                $bundleCard->save();
            }
            (new UpdateCuttingQtyInReportsAction)->setBundleCardGenerationDetail($bundleCardGenerationDetail)->handle();

            // $this->updateBundleCardGenerationCache($bundleCardGenerationDetail->id);
        });

        return redirect("/bundle-card-generation-manual/{$bundleCardGenerationDetail->id}");
    }

    public function searchManualBundleCardGenerations(Request $request)
    {
        $q = $request->q;
        if ($q == '') {
            return redirect('/bundle-card-generation-manual');
        }
        $bundleCardDetails = BundleCardGenerationDetail::getBundleGenerationListQuery(BundleCardGenerationDetail::MANUAL_BUNDLE_CARD_STATUS, $q)->paginate();

        return view('cuttingdroplets::pages.bundle_card_generation_list_manual', ['bundleCardDetails' => $bundleCardDetails, 'q' => $q]);
    }

    public function updateViewCache($id, Request $request)
    {
        $exception = DB::transaction(function () use ($id) {
            BundleCardGenerationDetail::query()
                ->whereRaw("sid IN (SELECT sid FROM bundle_card_generation_details WHERE id = $id)")
                ->where('is_manual', 1)
                ->get()
                ->map(function ($item) {
                    $this->updateBundleCardGenerationCache($item->id);
                });
        });

        if (is_null($exception)) {
            Session::flash('success', "View Page Successfully updated!");
        } else {
            Session::flash('failure', "Something went wrong!");
        }
        return \redirect('bundle-card-generation-manual');
    }
}
