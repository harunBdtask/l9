<?php

namespace SkylarkSoft\GoRMG\TQM\Services;


use SkylarkSoft\GoRMG\TQM\Models\TqmCuttingDhu;

class CuttingDhuReportDataService implements DhuReportContract
{
    public function handle(DhuReportStrategy $strategy): array
    {
        $reportData = TqmCuttingDhu::query()
            ->with(['cuttingTable:id,table_no', 'buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no'])
            ->whereDate('production_date', '>=', $strategy->getFromDate())
            ->whereDate('production_date', '<=', $strategy->getToDate())
            ->selectRaw('cutting_table_id, buyer_id, order_id, purchase_order_id,
                SUM(checked) as checked, SUM(qc_pass) as qc_pass, SUM(total_defect) as total_defect, SUM(reject) as reject')
            ->groupBy('cutting_table_id', 'buyer_id', 'order_id', 'purchase_order_id')
            ->get()
            ->map(function ($item) {
                return [
                    'table_no' => $item->cuttingTable->table_no ?? '',
                    'buyer_name' => $item->buyer->name ?? '',
                    'style_name' => $item->order->style_name ?? '',
                    'po_no' => $item->purchaseOrder->po_no ?? '',
                    'checked' => $item->checked ?? 0,
                    'qc_pass' => $item->qc_pass ?? 0,
                    'defects' => $item->total_defect ?? 0,
                    'dhu_level' => $item->checked ? (($item->total_defect * 100) / $item->checked) : 0.00,
                    'reject' => $item->reject ?? 0,
                ];
            });

        return (new DhuReportDataFormatter())
            ->setType($strategy->getType())
            ->setFromDate($strategy->getFromDate())
            ->setToDate($strategy->getToDate())
            ->setData($reportData)
            ->format();
    }
}
