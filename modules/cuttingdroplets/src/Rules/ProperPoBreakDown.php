<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;

class ProperPoBreakDown implements Rule
{
    private $lotRange = [];

    private $plys = [];

    private $ratio = [];

    private $garmentQty = [];

    private $lotsData = null;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($lotsData)
    {
        $this->lotsData = $lotsData;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public function passes($attribute, $value)
    {

        $this->setLotRange(
           request()->get('lot_id'),
           request()->get('from'),
           request()->get('to')
        );

        $this->setPlysByColor(
           request()->get('roll_no'),
           request()->get('ply')
        );

        $this->setRatio(
           request()->get('size_id'),
           request()->get('ratio')
        );

        $this->setGarmentQty();

        return $this->isValidGarmentQty(
            request()->get('color'),
            request()->get('size'),
            request()->get('quantity')
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Quantity mismatch.';
    }

    private function setLotRange($lots = [], $froms = [], $tos = [])
    {
        $data = [];

        foreach ($lots as $key => $lot) {
            $data[] = [
                'lot_id' => $lots[$key],
                'from' => $froms[$key],
                'to' => $tos[$key],
            ];
        }

        $this->lotRange = $data;
    }

    private function setPlysByColor($rolls = [], $plys = [])
    {
        $data = [];
        foreach ($rolls as $i => $roll) {
            $colorId = $this->getColorId($rolls[$i]);
            $data[$colorId] = ($data[$colorId] ?? 0) + $plys[$i];
        }

        $this->plys = $data;
    }

    private function setRatio($sizes = [], $ratios = [])
    {
        $data = [];

        foreach ($sizes as $i => $sizeId) {
            $data[$sizeId] = ($data[$sizeId] ?? 0) + $ratios[$i];
        }

        $this->ratio = $data;
    }

    private function getColorId($rollNo)
    {
        $lotId = 0;

        foreach ($this->lotRange as $lotRange) {
            if ($rollNo >= $lotRange['from'] && $rollNo <= $lotRange['to']) {
                $lotId = $lotRange['lot_id'];

                break;
            }
        }

        $lot = $this->lotsData ? $this->lotsData->where('id', $lotId)->first() : Lot::find($lotId);

        $colorId = $lot ? $lot->color_id : 0;

        return $colorId;
    }

    private function setGarmentQty()
    {
        $data = [];

        foreach ($this->ratio as $sizeId => $ratio) {
            foreach ($this->plys as $colorId => $plys) {
                $data[$colorId][$sizeId] = $plys * $ratio;
            }
        }

        $this->garmentQty = $data;
    }

    private function isValidGarmentQty($colors = [], $sizes = [], $quantities = [])
    {
        $garmentQty = [];

        foreach ($colors as $key => $colorId) {
            $garmentQty[$colorId][$sizes[$key]] = ($garmentQty[$colorId][$sizes[$key]] ?? 0) + $quantities[$key];
        }

        return $garmentQty == $this->garmentQty;
    }
}
