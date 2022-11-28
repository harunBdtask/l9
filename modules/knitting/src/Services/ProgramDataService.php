<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;

class ProgramDataService
{
    private $program_id;
    protected $totalRow = 0;

    public function __construct($programId)
    {
        $this->program_id = $programId;
    }

    public function response()
    {
        $knittingProgram = KnittingProgram::query()->with([
            'knittingProgramStripeDetails.itemColor',
            'planInfo.programmable.season',
            'knittingParty:id,factory_name',
            'knitting_program_colors_qtys',
            'factory:id,factory_name',
            'collarCuffs',
            'colorRange',
            'machines',
        ])->find($this->program_id);

        $allocationIterationFirstIndex = null;
        $knittingProgramColorsQtys = $knittingProgram->knitting_program_colors_qtys->map(function ($knitting_program_colors_qtys, $key)
        use ($knittingProgram, &$allocationIterationFirstIndex) {
            $knittingYarnGroup = $knitting_program_colors_qtys->knitting_yarns->groupBy('knitting_program_color_id')->values();
            $this->totalRow += count($knitting_program_colors_qtys->knitting_yarns);

            $colorData = [
                'id' => $knitting_program_colors_qtys->id,
                'item_color' => $knitting_program_colors_qtys->item_color,
                'program_qty' => $knitting_program_colors_qtys->program_qty,
                'booking_qty' => $knitting_program_colors_qtys->booking_qty,
                'item_color_id' => $knitting_program_colors_qtys->item_color_id,
                'knitting_program_id' => $knitting_program_colors_qtys->knitting_program_id,
            ];

            if (count($knittingYarnGroup) > 0) {
                if ($allocationIterationFirstIndex === null) {
                    $allocationIterationFirstIndex = $key;
                }
                return array_merge($colorData, [
                    'allocated_status' => true,
                    'knitting_yarns' => $knittingYarnGroup
                ]);
            } else {
                $this->totalRow += 1;
                return array_merge($colorData, [
                    'allocated_status' => false,
                    'knitting_yarns' => [
                        0 => [
                            0 => [
                                'yarn_description' => null,
                                'yarn_lot' => null,
                                'allocated_qty' => null,
                            ]
                        ]
                    ]
                ]);
            }
        });

        $knittingProgram->unsetRelation('knitting_program_colors_qtys');
        $knittingProgram->knitting_program_colors_qtys = $knittingProgramColorsQtys;
        $knittingProgram['allocation_iteration_first_index'] = $allocationIterationFirstIndex;
        $knittingProgram['total_row'] = $this->totalRow;

        return $knittingProgram;
    }
}
