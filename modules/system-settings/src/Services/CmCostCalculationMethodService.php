<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class CmCostCalculationMethodService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "text" => "{(SMV*CMP)*Costing Per +(SMV*CPM*Costing Per)*Efficeincy Wastage%}/Exchange Rate",
            ],
            [
                "id" => 2,
                "text" => "{(SMV*CMP)*Costing Per /Efficiency%+(Sewing SMV*CMP)*Costing Per /Sewing Efficiency%}/Exchange Rate",
            ],
            [
                "id" => 3,
                "text" => "{(MCE/WD)/NFM)*MPL)}/ [{(PHL)*WH}]*Costing Per/Exchange Rate",
            ],
            [
                "id" => 4,
                "text" => "{(CPM/Efficiency%)*SMV*Costing Per}/Exchange Rate",
            ],
            [
                "id" => 5,
                "text" => "((CPM/Efficiency%)*SMV)",
            ],
            [
                "id" => 6,
                "text" => "(CPM * SMV * COSTING PER)",
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
