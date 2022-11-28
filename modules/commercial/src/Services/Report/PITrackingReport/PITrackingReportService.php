<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Report\PITrackingReport;

use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;

class PITrackingReportService
{
    public $buyer, $fromDate, $toDate, $piNo;

    public function __construct($request)
    {
        $this->buyer = $request->get('buyer_id');
        $this->fromDate = $request->get('from_date');
        $this->toDate = $request->get('to_date');
        $this->piNo = $request->get('pi_no');
    }

    public function getReport()
    {
        return ProformaInvoice::query()
            ->with('details')
            ->whereDate('pi_created_date', '>=', $this->fromDate)
            ->whereDate('pi_created_date', '<=', $this->toDate)
            ->where('pi_basis', 1)
            ->when($this->piNo, Filter::applyFilter('pi_no', $this->piNo))
            ->get()
            ->map(function ($item) {
                $type = $this->getType(collect($item->details->details ?? [])->first());
                $details = $item->details->details ?? [];
                return [
                    'pi_no' => $item->pi_no ?? '',
                    'pi_created_date' => $item->pi_created_date ?? '',
                    'pi_value' => $item->details->net_total ?? 0,
                    'lc_no' => $item->lc_group_no ?? '',
                    'lc_date' => $item->lc_receive_date ?? '',
                    'type' => $type,
                    'details' => (new PITrackingReportDetailsStrategy)->setStrategy($type)->get($details)
                ];
            });
    }

    private function getType($item): ?string
    {
        if (!$item) {
            return null;
        }

        $type = $item->type ?? '';
        if ($type == 'main-fabric' || $type == 'main-trims') {
            return $type;
        }

        $woNo = explode('-', ($item->wo_no ?? ''));
        if ($woNo && isset($woNo[1]) && $woNo[1] == 'YPO') {
            $type = 'yarn-purchase';
        }

        return $type;
    }
}
