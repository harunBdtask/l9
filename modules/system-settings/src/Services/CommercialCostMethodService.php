<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class CommercialCostMethodService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "text" => "On Selling Price",
            ],
            [
                "id" => 2,
                "text" => "Yarn+ Trims+ Fabric Purchase",
            ],
            [
                "id" => 3,
                "text" => "Fabric Purchase + Trims Cost + Embellishment Cost + Garments Wash + Lab Test + Inspection +  Freight + Courier Cost + Certificate Cost + Design Cost + Studio Cost + Operating Expenses",
            ],
            [
                "id" => 4,
                "text" => "Fabric Purchase + Trims Cost + Embellishment Cost + Garments Wash + Lab Test + Inspection + CM Cost + Freight + Courier Cost + Certificate Cost + Design Cost + Studio Cost + Operating Expenses",
            ],
        ];
    }

    public static function all(): array
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
