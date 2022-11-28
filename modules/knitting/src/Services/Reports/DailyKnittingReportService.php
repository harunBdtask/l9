<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Reports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;

class DailyKnittingReportService
{
    private $from_date;
    private $to_date;

    public function __construct($request)
    {
        $this->from_date = $request->get('from_date');
        $this->to_date = $request->get('to_date');
    }

    public function report()
    {
        return KnittingProgram::query()
                ->with([
                    'planInfo',
                    'knitting_program_colors_qtys',
                ])
                ->whereDate('program_date', '>=', $this->from_date)
                ->whereDate('program_date', '<=', $this->to_date)
                ->latest()
                ->get()
                ->map(function ($program) {
                    return [
                        'machine_no' => collect($program->machine_nos)->implode(', '),
                        'program_no' => $program->program_no,
                        'booking_type' => $program->planInfo->booking_type ?? '',
                        'party_name' => $program->planInfo->buyer_name ?? '',
                        'stitch_length' => $program->stitch_length,
                        'fabric_gsm' => $program->planInfo->fabric_gsm ?? '',
                        'machine_dia' => $program->machine_dia,
                        'machine_gg' => $program->machine_gg,
                        'program_qty' => $program->program_qty,
                        'start_date' => date('d-m-Y', strtotime($program->start_date)),
                        'end_date' => date('d-m-Y', strtotime($program->end_date)),
                        'fabric_description' => $program->fabric_description,
                        'machine_feeder' => $program->feeder_text,
                        'finish_fabric_dia' => $program->finish_fabric_dia,
                        'remarks' => $program->remarks,
                        'knitting_yarns' => collect($program->knitting_program_colors_qtys)->map(function ($yarn) {
                            $yarnReceive = $this->yarnReceiveInfo($yarn->knitting_yarns);
                            return [
                                'color' => $yarn->item_color,
                                'program_color_qty' => $yarn->program_qty,
                                'yarn_lot' => collect($yarn->knitting_yarns)->pluck('yarn_lot')->unique()->values()->join(', '),
                                'yarn_brand' => collect($yarn->knitting_yarns)->pluck('yarn_brand')->unique()->values()->join(', '),
                                'yarn_description' => collect($yarn->knitting_yarns)->pluck('yarn_description')->unique()->values()->join(', '),
                                'pi_no' => $yarnReceive['pi_no'],
                                'yarn_ref' => $yarnReceive['yarn_ref'],
                                'allocated_qty' => collect($yarn->knitting_yarns)->sum('allocated_qty'),
                                'requisition_qty' => collect($yarn->knitting_yarns)->sum('previous_total_yarn_requisition_qty'),
                                'no_of_bag' => $yarnReceive['no_of_bag'],
                            ];
                        })
                    ];
                });
    }

    private function yarnReceiveInfo($yarns): array
    {
        $piNos = [];
        $yarnRefs = [];
        $noOfBag = [];
        foreach($yarns as $yarn) {
            $yarnReceiveDetail = YarnReceiveDetail::query()
                ->where(YarnItemAction::itemCriteria($yarn))
                ->with('yarnReceive')
                ->first();

            if ($yarnReceiveDetail && optional($yarnReceiveDetail->yarnReceive)->receive_basis == 'pi') {
                $piNos []= optional($yarnReceiveDetail->yarnReceive)->receive_basis_no;
            }
            $yarnRefs []= $yarnReceiveDetail ? $yarnReceiveDetail->product_code : '';
            if ($yarnReceiveDetail && !empty($yarnReceiveDetail->no_of_bag)) {
                $noOfBag []= $yarnReceiveDetail->no_of_bag;
            }
        }

        return [
            'pi_no' => collect($piNos)->implode(', '),
            'yarn_ref' => collect($yarnRefs)->implode(', '),
            'no_of_bag' => collect($noOfBag)->implode(', '),
        ];
    }
}
