<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\Fabric;

class ListService
{
    public static function getList($data): array
    {
        $groupedData = collect($data)
            ->groupBy(['job_no', 'body_part_id', 'construction', 'composition', 'dia_type', 'color_id', 'color_type_id', 'gsm',]);

        $finalData = [];

        foreach ($groupedData as $jobGroup) {
            foreach ($jobGroup as $bodyPartGroup) {
                foreach ($bodyPartGroup as $constructionGroup) {
                    foreach ($constructionGroup as $compositionGroup) {
                        foreach ($compositionGroup as $diaTypeGroup) {
                            foreach ($diaTypeGroup as $colorGroup) {
                                foreach ($colorGroup as $colorTypeGroup) {
                                    foreach ($colorTypeGroup as $gsmData) {
                                        $finalData[] = [
                                            'job_no' => collect($gsmData)->first()->job_no,
                                            'style_name' => collect($gsmData)->first()->budget->style_name ?? null,
                                            'po_no' => collect($gsmData)->pluck('po_no')->unique()->implode(', '),
                                            'po_no_values' => explode(",", collect($gsmData)->pluck('po_no')->unique()->implode(', ')),
                                            'construction' => collect($gsmData)->first()->construction,
                                            'composition' => collect($gsmData)->first()->composition,
                                            'gmt_color' => collect($gsmData)->pluck('gmt_color')->unique()->implode(', '),
                                            'gmt_color_values' => collect($gsmData)->pluck('gmt_color')->unique(),
                                            'item_color_values' => collect($gsmData)->pluck('item_color')->unique(),
                                            'body_part_value' => collect($gsmData)->first()->body_part_value,
                                            'dia' => collect($gsmData)->pluck('dia')->unique()->implode(', '),
                                            'dia_fin_type' => collect($gsmData)->pluck('dia_fin_type')->unique()->implode(', '),
                                            'wo_qty' => format(collect($gsmData)->sum('wo_qty')),
                                            'adj_qty' => format(collect($gsmData)->sum('adj_qty')),
                                            'moq_qty' => format(collect($gsmData)->sum('moq_qty')),
                                            'kg_cr' => format(collect($gsmData)->avg('kg_cr')),
                                            'actual_wo_qty' => round(collect($gsmData)->sum('actual_wo_qty')),
                                            'rate' => format(collect($gsmData)->avg('rate')),
                                            'amount' => format(collect($gsmData)->sum('amount')),
                                            'id' => collect($gsmData)->first()->id,
                                            'details' => $gsmData,
                                        ];
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }

        return $finalData;
    }
}
