<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationCache;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;

class UpdateBundleCardGenerationCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:update-bundle-card-generation-cache-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update BundleCardGenerationCache Model';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Command Initiated!');
            $sid = $this->ask('Enter SID for specific SID. Press Enter for ALL SIDs.');
            $is_manual = $this->ask('For only AUTO bundlecards type 1; For only Manual bundlecards type 0; For All bundlecards press enter');
            $skipExistingSids = $this->ask('Input 1 if you want to skip exiting SID. Otherwise press enter');
            DB::table('bundle_card_generation_details')
                ->select('id', 'sid', 'is_manual')
                ->when($sid, function ($query) use ($sid) {
                    $query->where('sid', $sid);
                })
                ->when($is_manual != null, function ($query) use ($is_manual) {
                    $query->where('is_manual', $is_manual);
                })
                ->when($skipExistingSids, function ($query) {
                    $query->whereRaw('sid NOT IN (SELECT sid FROM bundle_card_generation_caches GROUP BY sid)');
                })
                ->whereNull('deleted_at')
                ->orderBy('id', 'asc')
                ->chunk(200, function ($bundle_card_generation_details) {
                    foreach ($bundle_card_generation_details as $bundle_card_generation_detail) {
                        // code block
                        DB::beginTransaction();
                        $id = $bundle_card_generation_detail->id;
                        $with = [
                            'bundleCardsWithoutGlobalScopes:id,buyer_id,order_id,color_id,size_id,purchase_order_id,country_id,lot_id,status,roll_no,suffix,serial,cutting_no,bundle_no,size_wise_bundle_no,quantity,bundle_card_generation_detail_id,sl_overflow',
                            'bundleCardsWithoutGlobalScopes.lot:id,lot_no',
                            'bundleCardsWithoutGlobalScopes.color:id,name',
                            'bundleCardsWithoutGlobalScopes.order:id,style_name,reference_no',
                            'bundleCardsWithoutGlobalScopes.purchaseOrder:id,po_no,po_quantity',
                            'bundleCardsWithoutGlobalScopes.size:id,name',
                            'buyerWithoutGlobalScope:id,name',
                            'orderWithoutGlobalScope:id,style_name,reference_no',
                            'factory:id,factory_name,factory_address',
                            'partWithoutGlobalScope:id,name',
                            'typeWithoutGlobalScope:id,name',
                            'garmentsItem:id,name',
                            'cuttingFloorWithoutGlobalScope:id,floor_no',
                            'cuttingTableWithoutGlobalScope:id,table_no'
                        ];
                        $bundleCardGenerationDetail = BundleCardGenerationDetail::query()
                            ->withoutGlobalScope('factoryId')
                            ->with($with)
                            ->findOrFail($id);
                        $bundleCards = $bundleCardGenerationDetail->bundleCardsWithoutGlobalScopes->sortBy('bundle_no')->values();
                        if ($bundle_card_generation_detail->is_manual == 1) {
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
                            $bundleCardsData = [];
                            foreach ($bundleCards->toArray() as $key => $bundleCard) {
                                $bundleCardsData[$key] = $bundleCard;
                                $lot = Lot::withoutGlobalScope('factoryId')->find($bundleCard['lot_id']);
                                $bundleCardsData[$key]['lot'] = [
                                    'id' => $lot->id,
                                    'lot_no' => $lot->lot_no,
                                ];
                            }
                            $bundleCardGenerationDetails = $bundleCardGenerationDetail->toArray();
                            $bundleCardGenerationDetails['bundle_cards'] = $bundleCardGenerationDetail->bundleCardsWithoutGlobalScopes;
                            $bundleCardGenerationDetails['buyer'] = $bundleCardGenerationDetail->buyerWithoutGlobalScope;
                            $bundleCardGenerationDetails['order'] = $bundleCardGenerationDetail->orderWithoutGlobalScope;
                            $bundleCardGenerationDetails['part'] = $bundleCardGenerationDetail->partWithoutGlobalScope;
                            $bundleCardGenerationDetails['type'] = $bundleCardGenerationDetail->typeWithoutGlobalScope;
                            $bundleCardGenerationDetails['garments_item'] = $bundleCardGenerationDetail->garmentsItem;
                            $bundleCardGenerationDetails['cutting_floor'] = $bundleCardGenerationDetail->cuttingFloorWithoutGlobalScope;
                            $bundleCardGenerationDetails['cutting_table'] = $bundleCardGenerationDetail->cuttingTableWithoutGlobalScope;
                            $bundleCardGenerationDetails['order']['pq_qty_sum'] = $bundleCardGenerationDetail->orderWithoutGlobalScope->pq_qty_sum;
                        } else {
                            $stickers = $bundleCards->groupBy('color_id')->map(function ($item, $key) {
                                $sticker_sorting = 0;
                                $bundleCards = $item->map(function ($item, $key) {
                                    $item->size_suffix = $item->suffix ? $item->size->name . ' (' . $item->suffix . ')' : $item->size->name;
    
                                    return $item;
                                })->groupBy('size_suffix')->map(function ($item, $key) use (&$sticker_sorting){
                                    return [
                                        'sl_start' => explode('-', $item->first()->serial)[0],
                                        'sl_end' => explode('-', $item->last()->serial)[1],
                                        'color' => $item->first()->color->name,
                                        'cutting_no' => $item->first()->cutting_no,
                                        'sorting' => $sticker_sorting++
                                    ];
                                });
    
                                return $bundleCards;
                            });
                            $bundleCardsData = [];
                            foreach ($bundleCards->toArray() as $key => $bundleCard) {
                                $bundleCardsData[$key] = $bundleCard;
                                $lot = Lot::withoutGlobalScope('factoryId')->find($bundleCard['lot_id']);
                                $bundleCardsData[$key]['lot'] = [
                                    'id' => $lot->id,
                                    'lot_no' => $lot->lot_no,
                                ];
                            }
                            $bundleCardGenerationDetails = $bundleCardGenerationDetail->toArray();
                            $bundleCardGenerationDetails['bundle_cards'] = $bundleCardGenerationDetail->bundleCardsWithoutGlobalScopes;
                            $bundleCardGenerationDetails['buyer'] = $bundleCardGenerationDetail->buyerWithoutGlobalScope;
                            $bundleCardGenerationDetails['order'] = $bundleCardGenerationDetail->orderWithoutGlobalScope;
                            $bundleCardGenerationDetails['part'] = $bundleCardGenerationDetail->partWithoutGlobalScope;
                            $bundleCardGenerationDetails['type'] = $bundleCardGenerationDetail->typeWithoutGlobalScope;
                            $bundleCardGenerationDetails['garments_item'] = $bundleCardGenerationDetail->garmentsItem;
                            $bundleCardGenerationDetails['cutting_floor'] = $bundleCardGenerationDetail->cuttingFloorWithoutGlobalScope;
                            $bundleCardGenerationDetails['cutting_table'] = $bundleCardGenerationDetail->cuttingTableWithoutGlobalScope;
                            $bundleCardGenerationDetails['order']['pq_qty_sum'] = $bundleCardGenerationDetail->orderWithoutGlobalScope->pq_qty_sum;
                            $bundleCardGenerationDetails['roll_summary'] = $bundleCardGenerationDetail->roll_summary;
                            $bundleCardGenerationDetails['marker_piece'] = $bundleCardGenerationDetail->marker_piece;
                            $bundleCardGenerationDetails['bundle_summary'] = $bundleCardGenerationDetail->bundle_summary_without_scope;
                            $bundleCardGenerationDetails['allColors'] = $bundleCardGenerationDetail->all_colors_without_scope;
                            $bundleCardGenerationDetails['total_cutting_quantity_should_be'] = $bundleCardGenerationDetail->total_cutting_quantity_should_be;
                            $bundleCardGenerationDetails['quantity_save_or_loss'] = $bundleCardGenerationDetail->quantity_save_or_loss_without_scope;
                            $bundleCardGenerationDetails['used_consumption'] = $bundleCardGenerationDetail->used_consumption_without_scope;
                            $bundleCardGenerationDetails['consumption_save_or_loss'] = $bundleCardGenerationDetail->consumption_save_or_loss_without_scope;
                            $bundleCardGenerationDetails['fabric_save'] = $bundleCardGenerationDetail->fabric_save_without_scope;
                            $bundleCardGenerationDetails['result'] = $bundleCardGenerationDetail->result_without_scope;
                        }
                        $details = [
                            'bundleCardGenerationDetail' => $bundleCardGenerationDetails,
                            'bundleCards' => $bundleCardsData,
                            'stickers' => $stickers
                        ];
                        
                        $bundleCardGenerationCache = BundleCardGenerationCache::firstOrNew(['bg_id' => $id]);
                        $bundleCardGenerationCache->bg_id = $id;
                        $bundleCardGenerationCache->sid = $bundleCardGenerationDetail->sid;
                        $bundleCardGenerationCache->details = $details;
                        $bundleCardGenerationCache->save();
                        DB::commit();
                        $this->info("SID: " . $bundleCardGenerationDetail->sid . " ID: " . $bundleCardGenerationDetail->id);
                    }
                });
            $this->info("Command finished!");
        } catch (Exception $e) {
            $this->info($e->getMessage());
        }
        return 0;
    }
}
