<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\Fabric;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;

class BodyPartsService
{
    public static function getBodyParts($jobsNos): Collection
    {
        $data = Budget::with('fabricCosting')
            ->whereIn('job_no', $jobsNos)
            ->get()->map(function ($budget) {
                $fabric_data = collect($budget['fabricCosting']['details']['details']['fabricForm'])
                    ->transform(function ($data) use ($budget) {
                        return collect($data)->merge(['budget_id' => $budget['id']]);
                    });

                return [
                    'fabric' => $fabric_data->toArray(),
                ];
            });

        return collect($data)->flatten(2)->map(function ($f) {
            return [
                'name' => $f['body_part_value'] . ', ' . $f['color_type_value'] . ', ' . $f['fabric_composition_value'],
                'greyConsForm' => $f['greyConsForm'],
                'garment_item_id' => $f['garment_item_id'],
                'body_part_id' => $f['body_part_id'],
                'color_type_id' => $f['color_type_id'],
                'fabric_composition_id' => $f['fabric_composition_id'],
                'fabric_composition_value' => $f['fabric_composition_value'],
                'body_part_type' => $f['body_part_type'],
                'budget_id' => $f['budget_id'],
            ];
        })->whereIn('body_part_type', ['Flat Knit', 'Cuff'])->values();
    }
}
