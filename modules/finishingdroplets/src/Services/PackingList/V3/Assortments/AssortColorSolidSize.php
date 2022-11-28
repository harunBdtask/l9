<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3\Assortments;

class AssortColorSolidSize extends AbstractAssortment
{
    private function generateColorSizeRatio(): array
    {
        $colorSizeRatio = [];
        foreach ($this->getColors() as $color) {
            $colorSizeRatio[] = [
                "color_id" => $color['id'],
                "color_name" => $color['name'],
                "size_wise_ratio" => $this->generateSizeRatio(),
                "total_color_ratio" => 0,
                "total_color_qty_in_carton" => 0
            ];
        }
        return $colorSizeRatio;
    }

    private function generateSizeRatio(): array
    {
        $sizeRatio = [];
        foreach ($this->getSizes() as $size) {
            $sizeRatio[] = [
                "size_id" => $size['id'],
                "size_name" => $size['name'],
                "size_ratio" => 0,
                "total_size_qty_in_carton" => 0
            ];
        }
        return $sizeRatio;
    }

    private function generateCartonDetails(): array
    {
        $cartonDetails = [];
        $countSizes = collect($this->getSizes())->count();

        for ($i = 0; $i < $countSizes; $i++) {

            $cartonDetails[] = [
                "carton_from" => "",
                "carton_to" => "",
                "total_carton" => "",
                "unit_pc_per_carton" => "",
                "model_quality_art_destination" => "",
                "net_wt_in_kg" => "",
                "total_net_wt_in_kg" => "",
                "gross_wt_in_kg" => "",
                "total_gross_wt_in_kg" => "",
                "carton_length" => "",
                "carton_width" => "",
                "carton_height" => "",
                "carton_cbm" => "",
                "pack" => "",
                "colors" => $this->getColors(),
                "sizes" => [],
                "total_qty_in_carton" => "",
                "color_size_ratio" => $this->generateColorSizeRatio(),
                "remove_able" => false,
                "remarks" => "",
            ];
        }
        return $cartonDetails;
    }

    public function format(): array
    {
        return [
            'factory_id' => $this->getPo()->factory_id,
            'buyer_id' => $this->getPo()->buyer_id,
            'order_id' => $this->getPo()->order_id,
            'purchase_order_id' => $this->getPo()->id,
            'production_date' => null,
            'packing_ratio' => null,
            'carton_details' => $this->generateCartonDetails(),
            'color_size_wise_qty_breakdown' => $this->generateColoSizeMatrix(),
            'colors' => $this->getColors(),
            'sizes' => $this->getSizes(),
            'grand_total_cartons' => null,
            'grand_total_n_wt' => null,
            'grand_total_g_wt' => null,
            'grand_total_cbm' => null
        ];
    }
}
