<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

class IssueDetailsFormatter
{
    public function format($greyStoreIssue)
    {
        $loadRelations = [
            'issueDetails.fabricComposition',
            'issueDetails.fabricType',
            'issueDetails.color',
            'issueDetails.colorType',
            'issueDetails.unitOfMeasurement',
            'issueDetails.subTextileOperation',
            'issueDetails.subTextileProcess',
            'issueDetails.bodyPart',
        ];
        $greyStoreIssue->load($loadRelations);

        return $greyStoreIssue->issueDetails->map(function ($collection) {
            $balanceQty = StockSummeryService::setCriteria($collection)
                ->getBalance();

            return array_merge($collection->toArray(), ['grey_avl_stock_qty' => $balanceQty]);
        });
    }
}
