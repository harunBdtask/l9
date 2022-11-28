<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Handlers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class BundleCardGroupMarkerWiseSerialHandler
{
    protected $bundleCardGenerationDetail, $withoutScope, $groupMarkerStatus;

    public function __construct(BundleCardGenerationDetail $bundleCardGenerationDetail, $withoutScope, $groupMarkerStatus = false)
    {
        $this->bundleCardGenerationDetail = $bundleCardGenerationDetail;
        $this->withoutScope = $withoutScope;
        $this->groupMarkerStatus = $groupMarkerStatus;
    }

    public function handle()
    {
        $ratios = $this->bundleCardGenerationDetail->ratios;
        $rolls = $this->bundleCardGenerationDetail->rolls;
        $this->bundleCardGenerationDetail->poBreakDown = $this->bundleCardGenerationDetail->po_details ?? [];
        $maxBundleQty = $this->bundleCardGenerationDetail->max_quantity;
        $persistentBundleCards = $this->withoutScope ? $this->bundleCardGenerationDetail->bundleCardsWithoutGlobalScopes : $this->bundleCardGenerationDetail->bundleCards;
        $tube = $this->bundleCardGenerationDetail->is_tube + 1;

        $bundleSummary = [
            'bundle' => [],
            'bundle_cards' => [],
            'total_bundle' => 0,
            'total_quantity' => 0,
        ];
        //for group marker
        $totalPlys = 0;
        foreach ($rolls as $roll) {
            $totalPlys += $roll['plys'];
        }
        $slIncremental = ((int)($totalPlys / 100) * 100) + (($totalPlys % 100) > 0 ? 100 : 0);
        // $slIncremental = $totalPlys;
        
        $sizeWiseBundleNos = [];
        foreach ($ratios as $ratio) {
            $sizeWiseBundleNos[$ratio['size_name']] = 1;
            $startingSerial[$ratio['size_name']] = 0;
        }

        foreach ($ratios as $ratio) {
            $summary = [
                'serial' => $ratio['serial_no'],
                'size' => $ratio['size_name'],
                'suffix' => $ratio['suffix'],
                'bundles' => 0,
                'quantity' => 0
            ];

            $plyNo = 0;

            foreach ($rolls as $roll) {
                $qty = $roll['plys'] * $ratio['ratio'];
                $bundleNo = $bundleSummary['total_bundle'] + 1;
                $sizeWiseBundleNo = $sizeWiseBundleNos[$ratio['size_name']];

                if ($persistentBundleCards->isEmpty()) {
                    $bundleCards = $this->bundleCardGenerationDetail->generateBundleCards($bundleNo, $sizeWiseBundleNo, $plyNo, $ratio, $roll, $startingSerial, $slIncremental);
                    $bundlesPerRoll = count($bundleCards);

                    $sizeWiseBundleNos[$ratio['size_name']] += $bundlesPerRoll;
                    $summary['bundles'] += $bundlesPerRoll;
                    $bundleSummary['total_bundle'] += $bundlesPerRoll;

                    array_push($bundleSummary['bundle_cards'], ...$bundleCards);

                    $summary['quantity'] += $qty;
                    $plyNo += $roll['plys'];
                }
            }

            if (!$persistentBundleCards->isEmpty()) {
                $bundleCards = $persistentBundleCards
                    ->where('size_id', $ratio['size_id'])
                    ->where('suffix', $ratio['suffix']);

                $summary['bundles'] = $bundleCards->count();
                $bundleSummary['total_bundle'] += $summary['bundles'];

                if ($bundleCards->count()) {
                    array_push($bundleSummary['bundle_cards'], ...$bundleCards->toArray());
                }

                $summary['quantity'] = $bundleCards->sum('quantity');
            }

            $bundleSummary['bundle'][] = $summary;
            $bundleSummary['total_quantity'] += $summary['quantity'];
            if ($this->groupMarkerStatus) {
                $startingSerial[$ratio['size_name']] += $ratio['ratio'] * $slIncremental;
            }
        }
        return $bundleSummary;
    }
}
