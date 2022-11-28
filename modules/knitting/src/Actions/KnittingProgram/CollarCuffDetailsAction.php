<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram;

use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramCollarCuff;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;

class CollarCuffDetailsAction
{
    public function handle($knittingProgram)
    {
        $fabricBooking = FabricBooking::query()
            ->where('unique_id', $knittingProgram->booking_no)
            ->first();
        $planningInfo = PlanningInfo::query()
            ->where('booking_no', $knittingProgram->booking_no)
            ->first();
        $bodyPart = "$planningInfo->body_part, $planningInfo->color_type, $planningInfo->fabric_description";

        $collarCuff = collect($fabricBooking->collar_cuff_info ?? [])
            ->where('body_part', $bodyPart)
            ->pluck('details')
            ->flatten(1)
            ->map(function ($collection) use ($knittingProgram) {
                $collarCuff = [
                    'knitting_program_id' => $knittingProgram->id,
                    'booking_no' => $knittingProgram->booking_no,
                    'gmt_color_id' => $collection['color_id'] ?? null,
                    'gmt_color' => $collection['color'] ?? null,
                    'size_id' => $collection['size_id'] ?? null,
                    'size' => $collection['size'] ?? null,
                    'booking_item_size' => $collection['item_size'] ?? null,
                    'program_item_size' => null,
                    'booking_qty' => $collection['qty'] ?? null,
                    'excess_percentage' => null,
                ];
                KnittingProgramCollarCuff::query()->updateOrCreate([
                    'knitting_program_id' => $knittingProgram->id,
                    'gmt_color_id' => $collection['color_id'] ?? null,
                    'size_id' => $collection['size_id'] ?? null,
                ], $collarCuff);

                return $collarCuff;
            })->toArray();


    }
}
