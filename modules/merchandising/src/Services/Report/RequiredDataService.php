<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report;

use SkylarkSoft\GoRMG\Merchandising\Services\Month\MonthService;
use SkylarkSoft\GoRMG\SystemSettings\Models\AssigningFactory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class RequiredDataService
{
    public function getInfo(ReportViewService $reportViewService): array
    {
        $requiredData = array();
        $setData = $reportViewService->getRequiredData();

        if (array_key_exists('factory_id', $setData)) {
            $requiredData['factory'] = Factory::query()->where('id', $reportViewService->getFactoryId())->first()->factory_name ?? null;
            unset($setData['factory_id']);
        }

        if (array_key_exists('buyer_id', $setData)) {
            $requiredData['buyer'] = Buyer::query()->where('id', $reportViewService->getBuyerId())->first()->name ?? null;
            unset($setData['buyer_id']);
        }

        if (array_key_exists('season_id', $setData)) {
            $requiredData['season'] = Season::query()->where('id', $reportViewService->getSeasonId())->first()->season_name ?? null;
            unset($setData['season_id']);
        }

        if (array_key_exists('dealing_merchant_id', $setData)) {
            $requiredData['dealing_merchant'] = User::query()->where('id', $reportViewService->getDealingMerchantId())->first()->full_name ?? null;
            unset($setData['dealing_merchant_id']);
        }

        if (array_key_exists('assigning_factory_id', $setData)) {
            $requiredData['assigning_factory'] = AssigningFactory::query()->where('id', $reportViewService->getAssigningFactoryId())->first()->name ?? null;
            unset($setData['assigning_factory_id']);
        }

        if (array_key_exists('style_name', $setData)) {
            if (is_array($reportViewService->getStyleName())) {
                $requiredData['style'] = implode(', ', $reportViewService->getStyleName()) ?? null;
            } else {
                if ($reportViewService->getStyleName() !== null && $reportViewService->getStyleName() != 'null') {
                    $requiredData['style'] = $reportViewService->getStyleName() ?? null;
                }
            }
            unset($setData['style_name']);
        }

        if (array_key_exists('type', $setData)) {
            $requiredData['type'] = ucfirst(str_replace('_', ' ', $reportViewService->getType())) ?? null;
            unset($setData['type']);
        }

        if (array_key_exists('month', $setData)) {
            $requiredData['month'] = collect(MonthService::months())
                    ->where('id', $reportViewService->getMonth())
                    ->first()['text'] ?? null;
            unset($setData['month']);
        }

        if (array_key_exists('from_date', $setData) ) {
            $fromDate =  $reportViewService->getFromDate() ? date("F j, Y", strtotime( $reportViewService->getFromDate())) : null;
            $toDate = $reportViewService->getToDate() ? date("F j, Y", strtotime($reportViewService->getToDate())) : null;
            $toDate = $toDate ? ' - ' . $toDate : '';
            $requiredData['date'] = $fromDate . $toDate;
            if (isset($setData['to_date'])) unset($setData['to_date']);
            unset($setData['from_date']);
        }

        foreach (array_filter($setData) as $key => $value) {
            if (is_array($value)) {
                $requiredData[$key] = implode(', ', $value) ?? null;
            }
            else {
                $requiredData[$key] = $value;
            }
        }

        return $requiredData;
    }
}
