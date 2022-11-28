<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class ConsumptionBasisService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "name" => "Cad",
            ],
            [
                "id" => 2,
                "name" => "Measurement",
            ],
            [
                "id" => 3,
                "name" => "Marker",
            ],
        ];
    }

    public static function consumptionBasis(): array
    {
        return (new static())->data();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function get($id)
    {
        return collect((new static())->data())->where("id", $id)->first();
    }
}
