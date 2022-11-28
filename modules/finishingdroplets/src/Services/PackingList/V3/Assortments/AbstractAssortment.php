<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3\Assortments;

use Illuminate\Support\Collection;

abstract class AbstractAssortment
{
    protected $purchaseOrder;

    public abstract function format();

    public function setPo($purchaseOrder): self
    {
        $this->purchaseOrder = $purchaseOrder;
        return $this;
    }

    protected function getPo()
    {
        return $this->purchaseOrder;
    }

    protected function getColors()
    {
        return $this->getPo()->purchaseOrderDetails->unique('color_id')->pluck('color')->toArray();
    }

    protected function getSizes()
    {
        return $this->getPo()->purchaseOrderDetails->unique('size_id')->pluck('size')->toArray();
    }

    protected function generateColoSizeMatrix(): Collection
    {
        return collect($this->getColors())->crossJoin($this->getSizes())->map(function ($collection) {
            return [
                'color_id' => $collection[0]['id'],
                'color_name' => $collection[0]['name'],
                'size_id' => $collection[1]['id'],
                'size_name' => $collection[1]['name'],
                'qty' => 0,
                'ratio' => 0
            ];
        });
    }
}
