<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation;

use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;

class CostingMultiplierService
{
//    public static function generate($style_uom_val, $costing_per_val)
//    {
//        if ($style_uom_val == null) {
//            $style_uom = 0;
//        } else {
//            switch (PriceQuotation::STYLE_UOM[$style_uom_val]) {
//                case 'Pcs':
//                    $style_uom = 1;
//
//                    break;
//                case 'Set':
//                    $style_uom = 2;
//
//                    break;
//                default:
//                    $style_uom = 0;
//
//                    break;
//            }
//        }
//
//        if ($costing_per_val == null) {
//            $costing_per = 0;
//        } else {
//            switch (PriceQuotation::COSTING_PER[$costing_per_val]) {
//                case '1 Dzn':
//                    $costing_per = 12;
//
//                    break;
//                case '1 Pc':
//                    $costing_per = 1;
//
//                    break;
//                case '2 Dzn':
//                    $costing_per = 24;
//
//                    break;
//                case '3 Dzn':
//                    $costing_per = 36;
//
//                    break;
//                case '4 Dzn':
//                    $costing_per = 48;
//
//                    break;
//                default:
//                    $costing_per = 0;
//
//                    break;
//            }
//        }
//
//        return $style_uom * $costing_per;
//    }
    public static function generate($style_uom_val, $costing_per_val): int
    {
        if (isset(PriceQuotation::COSTING_PER[$costing_per_val])) {
            switch (PriceQuotation::COSTING_PER[$costing_per_val]) {
                case '1 Dzn':
                    $costing_per = 12;

                    break;
                case '1 Pc':
                    $costing_per = 1;

                    break;
                case '2 Dzn':
                    $costing_per = 24;

                    break;
                case '3 Dzn':
                    $costing_per = 36;

                    break;
                case '4 Dzn':
                    $costing_per = 48;

                    break;
                default:
                    $costing_per = 0;

                    break;
            }

            return $costing_per ?? 1;
        } else {
            return 1;
        }
    }
}
