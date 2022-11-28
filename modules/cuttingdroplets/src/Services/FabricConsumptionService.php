<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Cuttingdroplets\Notifications\FabricConsumptionFailureNotification;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class FabricConsumptionService
{
    private $bundleCardGenerationDetail;

    private function __construct($bundleCardGenerationDetail)
    {
        $this->bundleCardGenerationDetail = $bundleCardGenerationDetail;
        return $this;
    }

    public static function make($bundleCardGenerationDetail): self
    {
        return new static($bundleCardGenerationDetail);
    }

    private function getTotalWeight()
    {
        return collect($this->bundleCardGenerationDetail->rolls)->sum('weight');
    }

    private function getTotalQty()
    {
        return collect($this->bundleCardGenerationDetail->po_details)->sum('quantity');
    }

    private function getBookingCons(): float
    {
        return $this->bundleCardGenerationDetail->booking_consumption ?? 0;
    }

    private function calculateActualCons()
    {
        return $this->getTotalWeight() > 0 ? round((($this->getTotalWeight() / $this->getTotalQty()) * 12), 3) : 0;
    }

    private function generateSummaryReport(): array
    {
        $bookingDia = $this->bundleCardGenerationDetail['booking_dia'] ?? 0;
        $bookingGSM = $this->bundleCardGenerationDetail['booking_gsm'] ?? 0;
        $actualDia = round(collect($this->bundleCardGenerationDetail['rolls'])->avg('dia'), 3) ?? 0;
        $actualGSM = round(collect($this->bundleCardGenerationDetail['rolls'])->avg('gsm'), 3) ?? 0;
        $deviationDia = $actualDia - $bookingDia;
        $deviationGSM = $actualGSM - $bookingGSM;
        $deviationCons = round($this->calculateActualCons() - $this->getBookingCons(), 3);

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
                'consumption' => $this->getBookingCons(),
            ],
            'actual' => [
                'dia' => $actualDia,
                'gsm' => $actualGSM,
                'consumption' => $this->calculateActualCons(),
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

    public function result(): int
    {
        $deviationCons = round($this->calculateActualCons() - $this->getBookingCons(), 3);

        return $deviationCons > 0 ? 0 : 1;
    }

    public function notify()
    {
        $url = url("/bundle-card/{$this->bundleCardGenerationDetail->id}/cons-approval");

        $this->bundleCardGenerationDetail->load([
            'buyerWithoutGlobalScope:id,name',
            'orderWithoutGlobalScope:id,style_name',
            'garmentsItem:id,name',
            'bundleCards.purchaseOrder',
            'bundleCards.color',
            'cuttingTableWithoutGlobalScope',
            'partWithoutGlobalScope',
            'typeWithoutGlobalScope',
        ]);

        $approval = Approval::query()->where('page_name', Approval::FABRIC_CONS_APPROVAL)->first();
        if (isset($approval->user_id)) {
            $user = User::findOrFail($approval->user_id ?? userId());
            $user->notify(
                new FabricConsumptionFailureNotification(
                    $url,
                    $this->bundleCardGenerationDetail,
                    $this->generateSummaryReport()
                )
            );
        }

    }
}
