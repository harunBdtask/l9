<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Actions;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class UpdateCuttingQtyInReportsAction
{
    protected $bundleCardGenerationDetail;

    public function setBundleCardGenerationDetail(BundleCardGenerationDetail $bundleCardGenerationDetail)
    {
        $this->bundleCardGenerationDetail = $bundleCardGenerationDetail;
        return $this;
    }

    private function getBundleCardGenerationDetail()
    {
        return $this->bundleCardGenerationDetail;
    }

    public function handle()
    {
        $bundleCardGenerationDetailData = $this->getBundleCardGenerationDetail();
        $cutBundleCards = $bundleCardGenerationDetailData->bundleCards;
        (new UpdateTotalProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateColorSizeSummaryReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateDateWiseCuttingProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateDateAndColorWiseProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
        (new UpdateDateTableWiseCutProductionReportCutQtyAction)->setBundleCards($cutBundleCards)->handle();
    }
}