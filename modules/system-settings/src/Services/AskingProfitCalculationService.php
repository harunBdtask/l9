<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class AskingProfitCalculationService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "text" => "From Financial Parameter Setup",
            ],
            [
                "id" => 2,
                "text" => "Manual entry",
            ],
        ];
    }

    public static function all(): array
    {
        return (new static())->data();
    }

    public static function get($id)
    {
        return collect((new static())->data())->where("id", $id)->first();
    }
}
