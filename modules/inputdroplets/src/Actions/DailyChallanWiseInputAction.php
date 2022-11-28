<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Actions;

use SkylarkSoft\GoRMG\Inputdroplets\DTO\DailyChallanWiseInputDTO;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanSizeWiseInput;

class DailyChallanWiseInputAction
{
    protected $dailyChallanWiseInput;
    private $challanWiseInputDTO;
    private $bundleCard;

    private function __construct($challanWiseInputDTO)
    {
        $this->challanWiseInputDTO = $challanWiseInputDTO;
        $this->bundleCard = $this->challanWiseInputDTO->getCuttingInventory()->bundleCard;


        $this->dailyChallanWiseInput = $this->challanWiseInputDTO->getDailyChallanInputModel()
            ->firstOrNew($this->getCriteria(), [
                'buyer_id' => $this->bundleCard->buyer_id,
                'order_id' => $this->bundleCard->order_id,
                'factory_id' => $this->challanWiseInputDTO->getCuttingInventory()->factory_id,
            ]);
    }

    public static function make(DailyChallanWiseInputDTO $challanWiseInputDTO): self
    {
        return new static($challanWiseInputDTO);
    }

    private function getCriteria(): array
    {
        $criteria = [
            'floor_id' => $this->challanWiseInputDTO->getFloorId(),
            'line_id' => $this->challanWiseInputDTO->getLineId(),
            'purchase_order_id' => $this->bundleCard->purchase_order_id,
            'garments_item_id' => $this->bundleCard->garments_item_id,
            'color_id' => $this->bundleCard->color_id,
            'challan_no' => $this->challanWiseInputDTO->getCuttingInventory()->challan_no,
            'production_date' => $this->challanWiseInputDTO->getProductionDate(),
        ];

        if ($this->challanWiseInputDTO->getDailyChallanInputModel() instanceof DailyChallanSizeWiseInput) {
            $criteria['size_id'] = $this->bundleCard->size_id;
        }

        return $criteria;
    }

    public function setBundleQty($bundleQty): DailyChallanWiseInputAction
    {
        $this->dailyChallanWiseInput['sewing_input'] = $this->dailyChallanWiseInput['sewing_input'] ?? 0;
        $this->dailyChallanWiseInput['sewing_input'] += $bundleQty;
        return $this;
    }

    public function setNewFloorId($floorId): DailyChallanWiseInputAction
    {
        $this->dailyChallanWiseInput['floor_id'] = $floorId;
        return $this;
    }

    public function setNewLineId($lineId): DailyChallanWiseInputAction
    {
        $this->dailyChallanWiseInput['line_id'] = $lineId;
        return $this;
    }

    public function decreaseBundleQty($bundleQty): DailyChallanWiseInputAction
    {
        $this->dailyChallanWiseInput['sewing_input'] -= $bundleQty;
        return $this;
    }

    public function storeAndUpdate()
    {
        $this->dailyChallanWiseInput->save();
    }
}
