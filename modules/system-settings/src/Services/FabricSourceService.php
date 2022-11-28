<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class FabricSourceService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "name" => "Production",
            ],
            [
                "id" => 2,
                "name" => "Purchase",
            ],
            [
                "id" => 3,
                "name" => "Buyer Supplier",
            ],
            [
                "id" => 4,
                "name" => "Stock",
            ],
        ];
    }

    public static function fabricSource(): array
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
