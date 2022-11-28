<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class VariableService
{
    /**
     * @return array[]
     */
    private function data(): array
    {
        return [
            [
                "id" => 1,
                "name" => "All",
            ],
            [
                "id" => 2,
                "name" => "Price Quotation Copy to OE & Budget",
            ],
            [
                "id" => 3,
                "name" => "Budget Validate With  Price Quotation",
            ],
            [
                "id" => 4,
                "name" => "Style and Smv data Source",
            ],
            [
                "id" => 5,
                "name" => "Sample Requisition Fabric part Copy After Order Place",
            ],
            [
                "id" => 6,
                "name" => "Team Maintain",
            ],
            [
                "id" => 7,
                "name" => "Commercial Cost Method In PQ",
            ],
            [
                "id" => 8,
                "name" => "Cm Cost Calculation Method in PQ",
            ],
            [
                "id" => 9,
                "name" => "Asking Profit Calculation Method",
            ],
            [
                "id" => 10,
                "name" => "Commercial Cost Method In Budget",
            ],
            [
                "id" => 11,
                "name" => "Cm Cost Calculation Method in Budget",
            ],
            [
                "id" => 12,
                "name" => "Fabric Total Qty Calculation",
            ],
            [
                "id" => 13,
                "name" => "Budget Validation With Trims Booking",
            ],
            [
                "id" => 14,
                "name" => "Budget Approval Required For Booking",
            ],
            [
                "id" => 15,
                "name" => "PO Approval For Budget",
            ],
            [
                "id" => 16,
                "name" => "PDF and Excel Upload Maintain",
            ],
            [
                "id" => 17,
                "name" => "Adj. Qty Maintain",
            ],
            [
                "id" => 18,
                "name" => "TNA Maintain",
            ],
            [
                "id" => 19,
                "name" => "User Wise Task Maintain",
            ],
            [
                "id" => 20,
                "name" => "MOQ Qty Maintain",
            ],
            [
                "id" => 21,
                "name" => "Kg Conversion Maintain",
            ],
            [
                "id" => 22,
                "name" => "Over Receive Qty",
            ],
            [
                "id" => 23,
                "name" => "Trims Booking Contrast Color",
            ],
            [
                "id" => 24,
                "name" => "Color Types Maintain",
            ],

        ];
    }

    public static function variable(): array
    {
        return (new static())->data();
    }

    public static function get($id)
    {
        return collect((new static())->data())->where("id", $id)->first();
    }
}
