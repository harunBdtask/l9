<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class StyleSmvSourceService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "text" => "WS+PQ+OE+BOM",
            ],
            [
                "id" => 2,
                "text" => "WS+OE+BOM",
            ],
            [
                "id" => 3,
                "text" => "PQ+OE+BOM",
            ],
            [
                "id" => 4,
                "text" => "OE+BOM",
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
