<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Actions;

class UpdateSingleBundleCuttingQtyInReports
{
    protected $bundleCards;

    public function setBundleCards($bundleCards)
    {
        $this->bundleCards = $bundleCards;
        return $this;
    }

    private function getBundleCards()
    {
        return $this->bundleCards;
    }

    public function handle()
    {
        $cutBundleCards = $this->getBundleCards();
        (new UpdateTotalProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateColorSizeSummaryReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateDateWiseCuttingProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateDateAndColorWiseProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateDateTableWiseCutProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
    }
}