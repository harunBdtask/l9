<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramColorsQty;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationDetail;

class KnitProgramFabricColorService
{
    protected $knit_program_id, $plan_info_id;

    public function __construct($knit_program_id, $plan_info_id)
    {
        $this->knit_program_id = $knit_program_id;
        $this->plan_info_id = $plan_info_id;
    }

    public function formatData(): array
    {
        $getPlanInfoData = $this->getPlanInfoData($this->plan_info_id);
        $getExistingFabricColorData = $this->getExistingFabricColorData($this->plan_info_id, $this->knit_program_id);
        $data = [];
        $key = 0;
        foreach ($getPlanInfoData as $planInfoData) {
            $item_color_id = $planInfoData['item_color_id'];
            $existingData = $getExistingFabricColorData->where('item_color_id', $item_color_id)->first();
            $booking_qty = $planInfoData['booking_qty'];
            $program_qty = $existingData ? $existingData->program_qty : 0;
            $other_program_qty = KnittingProgramColorsQty::query()
                ->where('plan_info_id', $this->plan_info_id)
                ->where('item_color_id', $item_color_id)
                ->where('knitting_program_id', '!=', $this->knit_program_id)
                ->sum('program_qty');
            $balance_qty = $booking_qty - $program_qty - $other_program_qty;
            $max_program_qty = $booking_qty - $other_program_qty;

            $existingData
                ? $yarnAllocation = YarnAllocationDetail::query()
                    ->where('knitting_program_id', $this->knit_program_id)
                    ->where('knitting_program_color_id', $existingData->id ?? null)
                    ->sum('allocated_qty')
                : $yarnAllocation = 0;

            $data[$key]['id'] = $existingData ? $existingData->id : null;
            $data[$key]['item_color_id'] = $item_color_id;
            $data[$key]['item_color'] = $planInfoData['item_color'];
            $data[$key]['booking_qty'] = $booking_qty;
            $data[$key]['program_qty'] = $program_qty;
            $data[$key]['balance_qty'] = $balance_qty;
            $data[$key]['max_program_qty'] = $max_program_qty;
            $data[$key]['allocated_qty'] = $existingData ? $yarnAllocation : 0;

            $key++;
        }
        return $data;
    }
    /**
     * @param $plan_info_id
     * @return array
     * @description Plan info color wise qty by fabric description (FabricSalesOrderController:97)
     */
    private function getPlanInfoData($plan_info_id): array
    {
        $planInfoQuery = PlanningInfo::query()->findOrFail($plan_info_id);
        $data = [];
        if ($planInfoQuery) {
            $planInfoQuery->programmable
            ->load('bookingDetails')->get()
            ->map(function ($item, $key) use (&$data, $planInfoQuery) {
                $item->bookingDetails->whereIn('id', $planInfoQuery->details_ids)->groupBy('item_color_id')
                    ->map(function ($bookingData, $key) use (&$data) {
                    $data[] = [
                        'item_color_id' => $bookingData->first()->item_color_id,
                        'item_color' => $bookingData->first()->item_color,
                        'booking_qty' => $bookingData->sum('gray_qty'),
                    ];
                });
            });
        }

        return $data;
    }

    private function getExistingFabricColorData($plan_info_id, $knit_program_id)
    {
        return KnittingProgramColorsQty::query()
        ->where([
            'plan_info_id' => $plan_info_id,
            'knitting_program_id' => $knit_program_id,
        ])
        ->get();
    }
}
