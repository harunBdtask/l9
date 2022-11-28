<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class FabricPartCopyService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "text" => "Budget",
            ],
            [
                "id" => 2,
                "text" => "Manual Entry",
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
