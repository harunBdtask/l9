<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\DTO;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanWiseInput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class InputDashboardV2DTO
{
    private $date;
    private $floorId;
    private $floors;
    private $lines;
    private $inputData;
    private $outputData;
    private $inputOutputTarget;
    private $previousDayInput;

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function setFloorId($floorNo): void
    {
        $this->floorId = Floor::query()->where('floor_no', $floorNo)->value('id') ?? null;
    }

    public function getFloorId()
    {
        return $this->floorId;
    }

    private function getInputData(): self
    {
        $this->inputData = DailyChallanWiseInput::query()
            ->with([
                'floorsWithoutGlobalScopes',
                'linesWithoutGlobalScopes',
                'buyer',
                'order',
                'purchaseOrder.country',
                'color',
                'cuttingInventoryChallan:id,challan_no,created_at,updated_at'
            ])
            ->whereDate('production_date', $this->getDate())
            ->when($this->getFloorId(), function ($q) {
                return $q->where('floor_id', $this->getFloorId());
            })
            ->get();
        return $this;
    }

    public function getFloors(): self
    {
        $this->floors = $this->inputData->pluck('floor_id')->unique();
        return $this;
    }

    public function getLines(): self
    {
        $this->lines = $this->inputData->pluck('line_id')->unique();
        return $this;
    }

    private function getOutputData(): self
    {
        $this->outputData = FinishingProductionReport::query()
            ->select([
                'line_id',
                'floor_id',
                'sewing_output'
            ])
            ->whereDate('production_date', $this->getDate())
            ->whereIn('line_id', $this->lines)
            ->when($this->getFloorId(), function ($q) {
                return $q->where('floor_id', $this->getFloorId());
            })
            ->when(!$this->getFloorId(), function ($q) {
                return $q->whereIn('floor_id', $this->floors);
            })
            ->get();
        return $this;
    }

    private function getInputOutputTarget(): self
    {
        $this->inputOutputTarget = SewingLineTarget::query()
            ->whereIn('line_id', $this->lines)
            ->when($this->getFloorId(), function ($q) {
                return $q->where('floor_id', $this->getFloorId());
            })
            ->when(!$this->getFloorId(), function ($q) {
                return $q->whereIn('floor_id', $this->floors);
            })
            ->whereDate('target_date', $this->getDate())
            ->get();
        return $this;
    }

    private function getPreviousDayInputData(): self
    {
        $previousDate = Carbon::yesterday()->format('Y-m-d');

        $this->previousDayInput = DailyChallanWiseInput::query()
            ->select(['floor_id', 'line_id', 'sewing_input', 'production_date'])
            ->when($this->getFloorId(), function ($q) {
                return $q->where('floor_id', $this->getFloorId());
            })
            ->when(!$this->getFloorId(), function ($q) {
                return $q->whereIn('floor_id', $this->floors);
            })
            ->whereIn('line_id', $this->lines)
            ->whereDate('production_date', $previousDate)
            ->get();

        return $this;
    }

    private function format(): array
    {
        $data = [];
        foreach ($this->inputData->sortBy('linesWithoutGlobalScopes.sort')->groupBy('line_id') as $line_id => $reportByLine) {
            $floor_id = $reportByLine->first()->floor_id;
            $target = $this->inputOutputTarget
                ->where('line_id', $line_id)
                ->where('floor_id', $floor_id)
                ->first();

            $inputTarget = $target['input_plan'] ?? 0;
            $outputTarget = ($target['wh'] ?? 0) * ($target['target'] ?? 0);

            $outputQty = $this->outputData
                ->where('floor_id', $floor_id)
                ->where('line_id', $line_id)
                ->sum('sewing_output');

            $carryForward = $this->previousDayInput
                ->where('floor_id', $floor_id)
                ->where('line_id', $line_id)
                ->sum('sewing_input');

            $wip = $reportByLine->sum('sewing_input') - $outputQty;
            $challanDetails = [];
            foreach ($reportByLine as $report) {
                $challan_id = $report->cuttingInventoryChallan->id;
                $challan_originial_time = $report->cuttingInventoryChallan->updated_at;
                $new_challan_time = date('Y-m-d', strtotime($challan_originial_time)) . ' ';
                if (date('H', strtotime($challan_originial_time)) < 8) {
                    $new_challan_time .= '08:' . date('i:s', strtotime($challan_originial_time));
                } elseif (date('H', strtotime($challan_originial_time)) >= 19) {
                    $new_challan_time .= '18:' . date('i:s', strtotime($challan_originial_time));
                } else {
                    $new_challan_time .= date('H:i:s', strtotime($challan_originial_time));
                }
                $challanDetails[] = [
                    'buyer_id' => $report->buyer->name,
                    'buyer' => $report->buyer->name,
                    'order_id' => $report->order_id,
                    'merchandiser' => $report->order->dealingMerchant->screen_name,
                    'style' => $report->order->style_name,
                    'style_qty' => PurchaseOrderDetail::getItemWiseOrderQuantity($report->order_id, $report->garments_item_id),
                    'purchase_order_id' => $report->purchase_order_id,
                    'garments_item_id' => $report->garments_item_id,
                    'garments_item' => $report->garmentsItem->name,
                    'item_group' => $report->order->garmentsItemGroup->name,
                    'po_no' => $report->purchaseOrder->po_no,
                    'po_quantity' => PurchaseOrderDetail::getItemWisePoQuantity($report->purchase_order_id, $report->garments_item_id),
                    'country' => $report->purchaseOrder->country->name,
                    'color' => $report->color->name,
                    'color_qty' => PurchaseOrderDetail::getColorWisePoQuantity($report->purchase_order_id, $report->color_id),
                    'challan_id' => $challan_id,
                    'challan_no' => $report->challan_no,
                    'challan_time' => date('d/m/Y H:i:s', strtotime($new_challan_time)),
                    'challan_qty' => $report->sewing_input,
                ];
            }

            $day_input_qty = $reportByLine->sum('sewing_input');

            $data[] = [
                'floor_id' => $floor_id,
                'floor' => $reportByLine->first()->floorsWithoutGlobalScopes->floor_no,
                'line_id' => $line_id,
                'line' => $reportByLine->first()->linesWithoutGlobalScopes->line_no,
                'challanDetails' => $challanDetails,
                'day_input_target' => $inputTarget,
                'day_input_qty' => $day_input_qty,
                'input_due' => $inputTarget - $day_input_qty,
                'day_sewing_target' => $outputTarget,
                'day_output_qty' => $outputQty,
                'carry_forward' => $carryForward,
                'wip' => max($wip, 0),
            ];
        }

        return $data;
    }

    public function getReport(): array
    {
        return $this->getInputData()
            ->getFloors()
            ->getLines()
            ->getOutputData()
            ->getInputOutputTarget()
            ->getPreviousDayInputData()
            ->format();
    }
}
