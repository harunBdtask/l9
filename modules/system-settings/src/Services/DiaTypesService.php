<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class DiaTypesService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "name" => "Open",
            ],
            [
                "id" => 2,
                "name" => "Tube",
            ],
            [
                "id" => 3,
                "name" => "Niddle Open",
            ],
            [
                "id" => 4,
                "name" => "Any Dia",
            ],
        ];
    }

    public static function diaTypes(): array
    {
        return (new static())->data();
    }

    public static function get($id)
    {
        return collect((new static())->data())->where("id", $id)->first();
    }
}
