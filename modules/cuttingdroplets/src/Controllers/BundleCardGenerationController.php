<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;
use SkylarkSoft\GoRMG\Cuttingdroplets\Actions\UpdateCuttingQtyInReportsAction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationCache;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationView;
use SkylarkSoft\GoRMG\Cuttingdroplets\Requests\BundleCardRegenerateRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Requests\BundleCardRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\FabricConsumptionService;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\POQtyValidateService;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Type;

class BundleCardGenerationController extends Controller
{
    public function index()
    {
        $bundleCardDetails = BundleCardGenerationView::getBundleGenerationListQuery()->paginate();

        return view('cuttingdroplets::pages.bundle_card_generation_list', [
            'bundleCardDetails' => $bundleCardDetails
        ]);
    }

    public function create()
    {
        $buyers = Buyer::withoutGlobalScope('factoryId')->pluck('name', 'id')->all();
        $cuttingFloors = CuttingFloor::pluck('floor_no', 'id')->all();
        $countries = Country::pluck('name', 'id')->all();
        $parts = Part::withoutGlobalScope('factoryId')->pluck('name', 'id')->all();
        $types = Type::withoutGlobalScope('factoryId')->pluck('name', 'id')->all();
        $suffix = getBundleCardSuffixOptions();
        $garmentsProductionSettings = GarmentsProductionEntry::query()->where('factory_id', factoryId())->first();
        $sizeSuffixSerialEnabled = $garmentsProductionSettings->size_suffix_sl_status ?? 0;
        $customizedStickerSerials = $garmentsProductionSettings->customized_sticker_serials ?? null;
        $sizeSuffixSlOptions = BundleCardGenerationDetail::BUNDLE_CARD_SERIAL_OPTIONS;
        // if customized sticker serial is in the set
        if ($customizedStickerSerials && is_array($customizedStickerSerials) && count($customizedStickerSerials)) {
            $sizeSuffixSlOptions = collect(BundleCardGenerationDetail::BUNDLE_CARD_SERIAL_OPTIONS)->filter(function ($val, $key) use ($customizedStickerSerials) {
                return in_array($key, $customizedStickerSerials);
            })->toArray();
        }

        return view('cuttingdroplets::forms.bundle_card_generation_form', [
            'bundleCard' => null,
            'cuttingFloors' => $cuttingFloors,
            'buyers' => $buyers,
            'countries' => $countries,
            'parts' => $parts,
            'types' => $types,
            'suffix' => $suffix,
            'sizeSuffixSerialEnabled' => $sizeSuffixSerialEnabled,
            'sizeSuffixSlOptions' => $sizeSuffixSlOptions,
        ]);
    }

    public function store(BundleCardRequest $request)
    {
        try {
            $quantityBreakDown = [];
            $purchaseOrders = $request->get('purchase_order_id');
            $colors = $request->get('color');
            $sizes = $request->get('size');
            $quantities = $request->get('quantity');
            $countries = $request->get('country_id');

            $lotsData = Lot::query()->whereIn('id', $request->get('lot_id'))->get();
            $colorIds = $lotsData->pluck('color_id')->all();

            foreach ($purchaseOrders as $i => $order) {
                $quantityBreakDown[] = [
                    'purchase_order_id' => $order,
                    'color_id' => $colors[$i],
                    'size_id' => $sizes[$i],
                    'country_id' => $countries[$i] ?? null,
                    'quantity' => $quantities[$i]
                ];
            }

            $data = $request->except([
                'lot_id',
                'from',
                'to',
                'roll_no',
                'ply',
                'weight',
                'dia',
                'serial_no',
                'size_id',
                'suffix',
                'ratio',
                'purchase_order_id',
                'color',
                'size',
                'country_id',
                'quantity'
            ]);

            $data['lot_ranges'] = $this->getLotRanges(
                $lotsData,
                $request->get('lot_id'),
                $request->get('from'),
                $request->get('to')
            );

            $data['rolls'] = $this->getRolls(
                $request->get('roll_no'),
                $request->get('ply'),
                $request->get('weight'),
                $request->get('dia'),
                $request->get('gsm'),
            );

            $data['ratios'] = $this->getRatios(
                $request->get('serial_no'),
                $request->get('size_id'),
                $request->get('suffix'),
                $request->get('ratio')
            );

            $data['po_details'] = $quantityBreakDown;


            foreach ($colorIds as $colorId) {
                $data['colors'][] = [
                    'color_id' => $colorId
                ];
            }

            $cuttingNos = $request->get('cutting_nos');
            $data['cutting_no'] = '';
            foreach ($cuttingNos as $colorId => $cuttingNo) {
                $data['cutting_no'] .= $colorId . ': ' . $cuttingNo . '; ';
            }

            $data['cutting_no'] = rtrim($data['cutting_no'], '; ');
            $data['colors'] = json_encode($data['colors']);
            $isCreated = DB::transaction(function () use ($data, $lotsData) {
                $bundleCardGenerationDetail = BundleCardGenerationDetail::create(array_filter($data));
                $bundleCardGenerationDetail->sid = $bundleCardGenerationDetail->id;
                $bundleCardGenerationDetail->cons_result = FabricConsumptionService::make($bundleCardGenerationDetail)
                    ->result();
                $bundleCardGenerationDetail->save();
                $bundleCardGenerationDetail->used_lots = $lotsData;
                $bundleCards = $bundleCardGenerationDetail->bundle_summary['bundle_cards'];
                BundleCard::insert($bundleCards);
                if (isFabricConsApprovalEnabled() && !$bundleCardGenerationDetail->cons_result) {
                    FabricConsumptionService::make($bundleCardGenerationDetail)->notify();
                }
                // $this->updateBundleCardGenerationCache($bundleCardGenerationDetail->id);
            });

            if (!$isCreated) {
                Session::flash('success', S_SAVE_MSG);
            } else {
                Session::flash('failure', E_SAVE_MSG);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Session::flash('failure', $e->getMessage());
        }


        return redirect('bundle-card-generations');
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
        $stickers = $bundleCards->groupBy('color_id')->map(function ($item) {
            $sticker_sorting = 0;
            return $item->map(function ($item) {
                $item->size_suffix = $item->suffix ? $item->size->name . ' (' . $item->suffix . ')' : $item->size->name;

                return $item;
            })->groupBy('size_suffix')->map(function ($item) use (&$sticker_sorting) {
                return [
                    'sl_start' => explode('-', $item->first()->serial)[0],
                    'sl_end' => explode('-', $item->last()->serial)[1],
                    'color' => $item->first()->color->name,
                    'cutting_no' => $item->first()->cutting_no,
                    'sorting' => $sticker_sorting++
                ];
            });
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

    public function show($id)
    {
        if (BundleCardGenerationCache::where('bg_id', $id)->count()) {
            $cacheData = BundleCardGenerationCache::where('bg_id', $id)->first()->details;
            $lotIds = collect($cacheData['bundleCardGenerationDetail']['lot_ranges'])->pluck('lot_id')->toArray();
            $lots = Lot::query()->whereIn('id', $lotIds)->get();
            $data = [
                'bundleCardGenerationDetail' => $cacheData['bundleCardGenerationDetail'],
                'bundleCards' => collect($cacheData['bundleCards']),
                'stickers' => collect($cacheData['stickers']),
                'lots' => $lots
            ];
        } else {
            $bundleCardGenerationDetail = BundleCardGenerationDetail::query()
                ->with([
                    'order',
                    'bundleCards',
                    'bundleCards.color:id,name',
                    'bundleCards.size:id,name',
                    'bundleCards.purchaseOrder',
                ])
                ->withSum('purchaseOrderQtys as order_quantity', 'po_quantity')
                ->where('id', $id)->first();

            $bundleCards = $bundleCardGenerationDetail->bundleCards->sortBy('bundle_no')->values();
            $stickers = $bundleCards->groupBy('color_id')->map(function ($item) {
                $sticker_sorting = 0;
                return $item->map(function ($item) {
                    $item->size_suffix = $item->suffix ? $item->size->name . ' (' . $item->suffix . ')' : $item->size->name;

                    return $item;
                })->groupBy('size_suffix')->map(function ($item) use (&$sticker_sorting) {
                    return [
                        'sl_start' => explode('-', $item->first()->serial)[0],
                        'sl_end' => explode('-', $item->last()->serial)[1],
                        'color' => $item->first()->color->name,
                        'cutting_no' => $item->first()->cutting_no,
                        'sorting' => $sticker_sorting++
                    ];
                });
            });
            $lotIds = collect($bundleCardGenerationDetail['lot_ranges'])->pluck('lot_id')->toArray();
            $lots = Lot::query()->whereIn('id', $lotIds)->get();
            $colors = $bundleCardGenerationDetail->bundleCards->unique('color_id')->implode('color.name', ',');
            $pos = $bundleCardGenerationDetail->bundleCards->unique('purchase_order_id')->implode('purchaseOrder.po_no', ',');
            $data = [
                'bundleCardGenerationDetail' => $bundleCardGenerationDetail,
                'bundleCards' => $bundleCards,
                'stickers' => $stickers,
                'lots' => $lots,
                'colors' => $colors,
                'pos' => $pos,
            ];
        }

        $data['summaryReport'] = $this->generateSummaryReport($data);

        return view('cuttingdroplets::pages.bundle_card_generation_details', $data);
    }

    private function getRollSummaryData($bundleCardGenerationDetail, $key)
    {
        return isset($bundleCardGenerationDetail['roll_summary']) ? ($bundleCardGenerationDetail['roll_summary'][$key] ?? 0) : 0;
    }

    private function generateSummaryReport($data): array
    {
        $bundleCardGenerationDetail = $data['bundleCardGenerationDetail'];

        $bookingDia = $bundleCardGenerationDetail['booking_dia'] ?? 0;
        $bookingGSM = $bundleCardGenerationDetail['booking_gsm'] ?? 0;
        $bookingCons = $bundleCardGenerationDetail['booking_consumption'] ?? 0;
        $actualDia = round($this->getRollSummaryData($bundleCardGenerationDetail, 'average_dia'), 3) ?? 0;
        $actualGSM = round(($this->getRollSummaryData($bundleCardGenerationDetail, 'average_gsm') ?? 0), 3) ?? 0;
        $actualCons = $bundleCardGenerationDetail['bundle_summary']['total_quantity'] > 0 ?
            round((($this->getRollSummaryData($bundleCardGenerationDetail, 'total_weight')
                / $bundleCardGenerationDetail['bundle_summary']['total_quantity']) * 12), 3) : 0;
        $deviationDia = $actualDia - $bookingDia;
        $deviationGSM = $actualGSM - $bookingGSM;
        $deviationCons = round($actualCons - $bookingCons, 3);

        if ($deviationCons > 0) {
            $comments = 'Over';
            $result = 'Fail';
        } else if ($deviationCons == 0) {
            $comments = 'Equal';
            $result = 'Pass';
        } else {
            $comments = 'Less';
            $result = 'Good';
        }

        return [
            'booking' => [
                'dia' => $bookingDia,
                'gsm' => $bookingGSM,
                'consumption' => $bookingCons,
            ],
            'actual' => [
                'dia' => $actualDia,
                'gsm' => $actualGSM,
                'consumption' => $actualCons,
            ],
            'deviation' => [
                'dia' => $deviationDia,
                'gsm' => $deviationGSM,
                'consumption' => $deviationCons,
            ],
            'comments' => $comments,
            'result' => $result,
        ];
    }

    public function updateAll()
    {
        $genDetails = BundleCardGenerationDetail::get();

        foreach ($genDetails as $genDetail) {
            $lotRanges = [];
            foreach ($genDetail->lot_ranges as $lotRange) {
                $lot = Lot::find($lotRange['lot_id']);
                $lotRanges[] = [
                    'lot_id' => $lot->id,
                    'lot_no' => $lot->lot_no,
                    'lot_code' => $lot->lot_code,
                    'from' => $lotRange['from'],
                    'to' => $lotRange['to'],
                ];
            }
            $genDetail->lot_ranges = $lotRanges;
            $genDetail->save();
        }

        return redirect('cuttingdroplets::bundle-card-generations');
    }

    public function print($id)
    {
        if (BundleCardGenerationCache::where('bg_id', $id)->count()) {
            $cacheData = BundleCardGenerationCache::where('bg_id', $id)->first()->details;
            $lotIds = collect($cacheData['bundleCardGenerationDetail']['lot_ranges'])->pluck('lot_id')->toArray();
            $lots = Lot::query()->whereIn('id', $lotIds)->get();
            $data = [
                'bundleCardGenerationDetail' => $cacheData['bundleCardGenerationDetail'],
                'bundleCards' => collect($cacheData['bundleCards']),
                'stickers' => collect($cacheData['stickers']),
                'lots' => $lots
            ];
        } else {
            $bundleCardGenerationDetail = BundleCardGenerationDetail::query()
                ->with([
                    'order',
                    'bundleCards',
                    'bundleCards.color:id,name',
                    'bundleCards.size:id,name',
                    'bundleCards.purchaseOrder',
                ])
                ->withSum('purchaseOrderQtys as order_quantity', 'po_quantity')
                ->where('id', $id)->first();

            $bundleCards = $bundleCardGenerationDetail->bundleCards->sortBy('bundle_no')->values();
            $stickers = $bundleCards->groupBy('color_id')->map(function ($item) {
                $sticker_sorting = 0;
                return $item->map(function ($item) {
                    $item->size_suffix = $item->suffix ? $item->size->name . ' (' . $item->suffix . ')' : $item->size->name;

                    return $item;
                })->groupBy('size_suffix')->map(function ($item) use (&$sticker_sorting) {
                    return [
                        'sl_start' => explode('-', $item->first()->serial)[0],
                        'sl_end' => explode('-', $item->last()->serial)[1],
                        'color' => $item->first()->color->name,
                        'cutting_no' => $item->first()->cutting_no,
                        'sorting' => $sticker_sorting++
                    ];
                });
            });
            $lotIds = collect($bundleCardGenerationDetail['lot_ranges'])->pluck('lot_id')->toArray();
            $lots = Lot::query()->whereIn('id', $lotIds)->get();
            $colors = $bundleCardGenerationDetail->bundleCards->unique('color_id')->implode('color.name', ',');
            $pos = $bundleCardGenerationDetail->bundleCards->unique('purchase_order_id')->implode('purchaseOrder.po_no', ',');
            $data = [
                'bundleCardGenerationDetail' => $bundleCardGenerationDetail,
                'bundleCards' => $bundleCards,
                'stickers' => $stickers,
                'lots' => $lots,
                'colors' => $colors,
                'pos' => $pos,
            ];
        }
        if (getBundleCardPrintStyle() == 1) {
            $view = view('cuttingdroplets::pages.print_bundlecard_barcode', $data);
        } else {
            $data['summaryReport'] = $this->generateSummaryReport($data);
            $view = view('cuttingdroplets::pages.print_bundlecards', $data);
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
            Session::flash('failure', 'Sorry!! Cannot delete because already sent to print!');
            return redirect()->back();
        } elseif ($cuttingInventories) {
            Session::flash('failure', 'Sorry!! Cannot delete because already sent to input/tag!');
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

    public function scanBundleCards($id)
    {
        $cutting_date = operationDate();

        $bundleCardGenerationDetail = BundleCardGenerationDetail::with('bundleCards')->findOrFail($id);
        $qtyExceeds = (new POQtyValidateService())->isMaxPOQtyExceeds($bundleCardGenerationDetail);

        if ($qtyExceeds) {
            Session::flash('failure', 'Max po qty exceeds!');
            return redirect()->back();
        }

        DB::transaction(function () use ($bundleCardGenerationDetail, $cutting_date) {
            $bundle_card_generation_detail_id = $bundleCardGenerationDetail->sid;

            $bundleUpdatedInfo = [
                'status' => 1,
                'cutting_qc_challan_no' => $bundleCardGenerationDetail->id,
                'cutting_challan_no' => $bundleCardGenerationDetail->id,
                'cutting_challan_status' => 1,
                'cutting_qc_challan_status' => 0,
                'cutting_date' => $cutting_date,
                'updated_by' => userId(),
            ];

            DB::table('bundle_cards')
                ->where('bundle_card_generation_detail_id', $bundle_card_generation_detail_id)
                ->update($bundleUpdatedInfo);

            (new UpdateCuttingQtyInReportsAction)->setBundleCardGenerationDetail($bundleCardGenerationDetail)->handle();

            // $this->updateBundleCardGenerationCache($bundleCardGenerationDetail->id);
        });

        return redirect("/bundle-card-generations/{$bundleCardGenerationDetail->id}");
    }

    private function getLotRanges($lotsData, $lots = [], $froms = [], $tos = [])
    {
        $data = [];
        $lotsData = $lotsData->keyBy('id');
        foreach ($lots as $key => $lot) {
            // $lot = Lot::findOrFail($lots[$key]);
            $lotInfo = $lotsData[$lot];

            $data[] = [
                'lot_id' => $lotInfo->id,
                'lot_no' => $lotInfo->lot_no,
                'lot_code' => $lotInfo->lot_code,
                'from' => $froms[$key],
                'to' => $tos[$key],
            ];
        }

        return $data;
    }

    private function getRolls($rolls = [], $plys = [], $weights = [], $dias = [], $gsms = [])
    {
        $data = [];

        foreach ($rolls as $i => $roll) {
            $data[] = [
                'roll_no' => $rolls[$i],
                'plys' => $plys[$i],
                'weight' => $weights[$i],
                'dia' => $dias[$i],
                'gsm' => $gsms[$i],
            ];
        }

        return $data;
    }

    private function getRatios($serials = [], $sizes = [], $suffixs = [], $ratios = [])
    {
        $data = [];

        $sizesData = Size::query()->whereIn('id', $sizes)->get()->keyBy('id');

        foreach ($serials as $i => $serial) {
            // $size = Size::findOrFail($sizes[$i]);
            $size = $sizesData[$sizes[$i]];

            $data[] = [
                'serial_no' => $serials[$i],
                'size_id' => $size->id,
                'size_name' => $size->name,
                'size_code' => $size->code,
                'suffix' => $suffixs[$i],
                'ratio' => $ratios[$i]
            ];
        }

        return $data;
    }

    public function reGenerateForm($id)
    {
        $parts = Part::pluck('name', 'id')->all();
        $types = Type::withoutGlobalScope('factoryId')->pluck('name', 'id')->all();

        return view('cuttingdroplets::forms.bundle_card_re_generation_form', [
            'parts' => $parts,
            'types' => $types,
        ]);
    }

    public function reGenerate($id, BundleCardRegenerateRequest $request)
    {
        $genDetail = BundleCardGenerationDetail::findOrFail($id);
        $newGenDetail = $genDetail->replicate();

        $isRegenerated = DB::transaction(function () use ($request, $newGenDetail) {
            $newGenDetail->part_id = $request->get('part_id');
            $newGenDetail->type_id = $request->get('type_id');
            $newGenDetail->is_regenerated = 1;
            $newGenDetail->save();

            // $this->updateBundleCardGenerationCache($newGenDetail->id);
        });

        if (!$isRegenerated) {
            Session::flash('success', 'Successfully regenerated');
        } else {
            Session::flash('failure', 'Not Successfully regenerated');
        }

        return redirect('bundle-card-generations');
    }

    private function getReplaceBundleCardDataV1($bundleCardId)
    {
        return BundleCard::where('id', $bundleCardId)
            ->with([
                'buyer:id,name',
                'order:id,style_name,reference_no',
                'purchaseOrder:id,po_no',
                'cuttingTable:id,table_no',
                'color:id,name',
                'size:id,name',
                'lot:id,lot_no',
            ])
            ->first();
    }

    private function getReplaceBundleCardDataV2($bundleCardId)
    {
        return DB::select("SELECT a.*, SUM(`purchase_orders`.`po_quantity`) as po_quantity FROM
            (SELECT `bundle_cards`.`id` as bundle_id, `bundle_cards`.`buyer_id` as buyer_id,
            `bundle_cards`.`order_id` as order_id, `bundle_cards`.`purchase_order_id` as purchase_order_id,
            `bundle_cards`.`color_id` as color_id, `bundle_cards`.`size_id` as size_id,
            `bundle_cards`.`cutting_table_id` as cutting_table_id, `bundle_cards`.`lot_id` as lot_id,
            `bundle_cards`.`quantity` as quantity, `bundle_cards`.`sl_overflow` as sl_overflow,
            `bundle_cards`.`bundle_no` as bundle_no, `bundle_cards`.`size_wise_bundle_no` as size_wise_bundle_no,
            `bundle_cards`.`serial` as `serial`, `bundle_cards`.`cutting_no` as cutting_no,
            `bundle_cards`.`created_at` as created_at, `lots`.`lot_no` as lot_no,
            `buyers`.`name` as buyer_name, `orders`.`style_name` as style_name,
            `orders`.`reference_no` as reference_no, `purchase_orders`.`po_no` as po_no,
            `colors`.`name` as color_name, `sizes`.`name` as size_name, `cutting_tables`.`table_no` as table_no,
            `parts`.`name` as part_name, `types`.`name` as `type_name`,
            `factories`.`factory_short_name` as factory_short_name
            FROM `bundle_cards`
            JOIN `bundle_card_generation_details` on `bundle_card_generation_details`.`id` = `bundle_cards`.`bundle_card_generation_detail_id`
            LEFT JOIN `buyers` on `buyers`.`id` = `bundle_cards`.`buyer_id`
            LEFT JOIN `orders` on `orders`.`id` = `bundle_cards`.`order_id`
            LEFT JOIN `purchase_orders` on `purchase_orders`.`id` = `bundle_cards`.`purchase_order_id`
            LEFT JOIN `colors` on `colors`.`id` = `bundle_cards`.`color_id`
            LEFT JOIN `sizes` on `sizes`.`id` = `bundle_cards`.`size_id`
            LEFT JOIN `cutting_tables` on `cutting_tables`.`id` = `bundle_cards`.`cutting_table_id`
            LEFT JOIN `lots` on `lots`.`id` = `bundle_cards`.`lot_id`
            LEFT JOIN `parts` on `parts`.`id` = `bundle_card_generation_details`.`part_id`
            LEFT JOIN `types` on `types`.`id` = `bundle_card_generation_details`.`type_id`
            LEFT JOIN `factories` on `factories`.`id` = `bundle_cards`.`factory_id`
            WHERE `bundle_cards`.`id` = $bundleCardId AND `bundle_cards`.`deleted_at` IS NULL)a
            LEFT JOIN `purchase_orders` on `purchase_orders`.`order_id` = a.`order_id`
            WHERE `purchase_orders`.`deleted_at` IS NULL
        ")[0];
    }

    public function replcaeBundleCard(Request $request)
    {
        $bundle = null;
        if ($request->isMethod('post')) {
            $bundleCardId = ltrim(substr($request->get('barcode'), 1, 9), 0);
            $bundle = $this->getReplaceBundleCardDataV2($bundleCardId);

            if (empty($bundle)) {
                Session::flash('error', 'Sorry!! Please enter valid barcode');
            }
        }
        return view('cuttingdroplets::forms.bundle_card_replace')->with('bundle', $bundle);
    }

    /**
     * get cutting no dropdron
     */
    public function getCuttingNo($buyer_id, $purchase_order_id, $color_id)
    {
        $data = BundleCard::where(['purchase_order_id' => $purchase_order_id, 'color_id' => $color_id])
            ->get()
            ->groupBy('cutting_no');

        $result = [];
        foreach ($data as $key => $cn_no) {
            $value = $cn_no->first();
            $result[$value->id] = $value->cutting_no;
        }
        return $result;
    }

    /**
     * Update get cutting no dropdown select search
     */
    public function getCuttingNoByPoColor()
    {
        $purchaseOrderId = request('purchase_order_id') ?? null;
        $colorId = request('color_id') ?? null;
        $search = request('search') ?? null;
        $cutting_nos = [];
        if ($purchaseOrderId && $colorId) {
            BundleCard::query()
                ->select('cutting_no')
                ->where(['purchase_order_id' => $purchaseOrderId, 'color_id' => $colorId])
                ->when($search, function ($q) use ($search) {
                    $q->where('cutting_no', 'like', '%' . $search . '%');
                })
                ->groupBy('cutting_no')
                ->get()
                ->map(function ($item, $key) use (&$cutting_nos) {
                    $cutting_nos[] = [
                        'id' => $item->cutting_no,
                        'text' => $item->cutting_no,
                    ];
                });
        }
        return response()->json([
            'results' => $cutting_nos
        ]);
    }

    public function searchBundleCardGenerations(Request $request)
    {
        $q = $request->q ?? null;
        if (!$request->q) {
            return redirect('/bundle-card-generations');
        }
        $bundleCardDetails = BundleCardGenerationDetail::getBundleGenerationListQuery(BundleCardGenerationDetail::AUTO_BUNDLE_CARD_STATUS, $q)->paginate();

        return view('cuttingdroplets::pages.bundle_card_generation_list', ['bundleCardDetails' => $bundleCardDetails, 'q' => $request->q]);
    }

    public function updateViewCache($id, Request $request)
    {
        $exception = DB::transaction(function () use ($id) {
            BundleCardGenerationDetail::query()
                ->whereRaw("sid IN (SELECT sid FROM bundle_card_generation_details WHERE id = $id)")
                ->where('is_manual', 0)
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
        return \redirect('bundle-card-generations');
    }
}
