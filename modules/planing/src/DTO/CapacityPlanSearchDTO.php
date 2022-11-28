<?php

namespace SkylarkSoft\GoRMG\Planing\DTO;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Planing\Models\FactoryCapacity;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class CapacityPlanSearchDTO
{
    private $factoryId;
    private $floorId;
    private $date;

    /**
     * @return mixed
     */
    public function getFactoryId()
    {
        return $this->factoryId;
    }

    /**
     * @param mixed $factoryId
     */
    public function setFactoryId($factoryId): void
    {
        $this->factoryId = $factoryId;
    }

    /**
     * @return mixed
     */
    public function getFloorId()
    {
        return $this->floorId;
    }

    /**
     * @param mixed $floorId
     */
    public function setFloorId($floorId): void
    {
        $this->floorId = $floorId;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    private function getFirstDateOfMonth(): string
    {
        return Carbon::make($this->getDate())
            ->firstOfMonth()
            ->format('Y-m-d');
    }

    private function getSearchCriteria(): array
    {
        return [
            'factory_id' => $this->getFactoryId(),
            'floor_id' => $this->getFloorId(),
            'date' => $this->getFirstDateOfMonth(),
        ];
    }

    private function getFactoryCapacity()
    {
        return FactoryCapacity::query()->where($this->getSearchCriteria())->get();
    }

    private function getLine()
    {
        return Line::query()
            ->withoutGlobalScope('factoryId')
            ->where([
                'factory_id' => $this->getFactoryId(),
                'floor_id' => $this->getFloorId(),
            ])
            ->orderBy('sort', 'ASC')
            ->get();
    }

    private function formatFactoryCapacityForm(): array
    {
        $factoryCapacityForm = [];
        foreach ($this->getLine() as $line) {
            $lineWiseFactoryCapacities = $this->getFactoryCapacity()->where('line_id', $line->id)->all();

            if (collect($lineWiseFactoryCapacities)->count()) {
                foreach ($lineWiseFactoryCapacities as $lineWiseFactoryCapacity) {
                    $factoryCapacityForm[] = $this->formatLineCapacity($line, $lineWiseFactoryCapacity);
                }
            } else {
                $factoryCapacityForm[] = $this->formatLineCapacity($line);
            }
        }

        return $factoryCapacityForm;
    }

    private function formatLineCapacity($line, $lineWiseFactoryCapacity = null): array
    {

        $smv = $lineWiseFactoryCapacity->smv ?? 0;

        $response = [
            "id" => $lineWiseFactoryCapacity->id ?? null,
            "date" => $this->getFirstDateOfMonth(),
            "factory_id" => $this->getFactoryId(),
            "floor_id" => $this->getFloorId(),
            "line_id" => $line->id,
            "line" => $line->line_no,
            "sort" => $line->sort,
//            "garments_item_id" => $lineWiseFactoryCapacity->garments_item_id ?? null,
            "item_category_id" => $lineWiseFactoryCapacity->item_category_id ?? null,
            "smv" => $lineWiseFactoryCapacity->smv ?? null,
            "efficiency" => $lineWiseFactoryCapacity->efficiency ?? null,
            "operator_machine" => $lineWiseFactoryCapacity->operator_machine ?? 0,
            "helper" => $lineWiseFactoryCapacity->helper ?? 0,
            "wh" => $lineWiseFactoryCapacity->wh ?? 0,
            "capacity_pcs" => $smv == 0 ? 0 :
                round($lineWiseFactoryCapacity->capacity_available_mins / $smv),
            "capacity_available_mins" => $lineWiseFactoryCapacity->capacity_available_mins ?? 0,
        ];

        return collect($response)->sortBy('sort')->toArray();
    }

    public function getFactoryCapacityForm(): array
    {
        return $this->formatFactoryCapacityForm();
    }
}
