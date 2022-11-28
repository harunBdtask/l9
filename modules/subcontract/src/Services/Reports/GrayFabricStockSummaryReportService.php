<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\Reports;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class GrayFabricStockSummaryReportService
{
    public function getReportData(Request $request)
    {
        $partyId = $request->get('party_id');
        $buyers = Buyer::query()
            ->when($partyId && $partyId != 'all', function ($query) use ($partyId) {
                $query->where('id', $partyId);
            })
            ->get()->map(function ($buyer) {
                $totalReceiveQuantity = SubGreyStoreReceiveDetails::query()
                    ->where('supplier_id', $buyer->id)
                    ->sum('receive_qty');

                $totalBatchQuantity = SubDyeingBatchDetail::query()
                    ->where('supplier_id', $buyer->id)
                    ->sum('batch_weight');

                return [
                    'name' => $buyer['name'],
                    'total_receive_qty' => $totalReceiveQuantity,
                    'total_batch_qty' => $totalBatchQuantity,
                    'closing_stock' => $totalReceiveQuantity - $totalBatchQuantity,
                ];
            });

        return [
            'buyers' => $buyers,
        ];
    }
}
