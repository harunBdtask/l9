<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\GreyReceive;

use DNS1D;
use Illuminate\Support\Collection;

class GreyReceiveFormatterService
{

    public static function formatChallan($data): Collection
    {
        return collect($data)->map(function ($item) {
            $scanable_barcode = $item['knitting_program_roll_id'] ? str_pad($item['knitting_program_roll_id'], 9, '0', STR_PAD_LEFT) : '';

            $yarnDescription = collect($item['knittingProgram']['yarnRequisition']['details'] ?? [])->map(function ($item) {
                $yarn_lot = $item['yarn_lot'] ?? null;
                $yarn_brand = $item['yarn_brand'] ?? null;
                $yarn_composition = $item['composition']['yarn_composition'] ?? null;
                $yarn_count = $item['yarn_count']['yarn_count'] ?? null;
                return '[' . $yarn_lot . ', ' . $yarn_count . ', ' . $yarn_brand . ', ' . $yarn_composition . ']';
            });

            return [
                'item' => $item,
                'id' => null,
                'factory_id' => $item['factory_id'] ?? null,
                'grey_receive_id' => null,
                'factory_name' => $item['factory']['factory_name'] ?? null,
                'knitting_program_id' => $item['knitting_program_id'] ?? null,
                'plan_info_id' => $item['plan_info_id'] ?? null,
                'knitting_program_roll_id' => $item['knitting_program_roll_id'] ?? null,
                'book_company' => $item['knittingProgram']['knittingParty']['factory_name'] ?? null,
                'knitting_source_value' => $item['knittingProgram']['knitting_source_value'] ?? null,
                'buyer_name' => $item['planningInfo']['buyer_name'] ?? null,
                'style_name' => $item['planningInfo']['style_name'] ?? null,
                'unique_id' => $item['planningInfo']['unique_id'] ?? null,
                'po_no' => $item['planningInfo']['po_no'] ?? null,
                'booking_no' => $item['planningInfo']['booking_no'] ?? null,
                'body_part' => $item['planningInfo']['body_part'] ?? null,
                'color_type' => $item['planningInfo']['color_type'] ?? null,
                'fabric_description' => $item['planningInfo']['fabric_description'] ?? null,
                'item_color' => $item['planningInfo']['item_color'] ?? null,
                'program_no' => $item['knittingProgram']['program_no'] ?? null,
                'production_qty' => $item['knittingProgram']['production_qty'] ?? null,
                'pcs_production_qty' => $item['knitProgramRoll']['production_pcs_total'] ?? null,
                'scanable_barcode' => $scanable_barcode,
                'barcode_view' => DNS1D::getBarcodeSVG(($scanable_barcode), "C128A", 1, 16, '', false),
                'yarn_composition_id' => collect($item['knittingProgram']['yarnRequisition']['details'] ?? [])->pluck('composition.id')->values() ?? null,
                'yarn_count_id' => collect($item['knittingProgram']['yarnRequisition']['details'] ?? [])->pluck('yarn_count.id')->values() ?? null,
                'yarn_count_value' => collect($item['knittingProgram']['yarnRequisition']['details'] ?? [])->pluck('yarn_count.yarn_count')->values() ?? null,
                'yarn_composition_value' => collect($item['knittingProgram']['yarnRequisition']['details'] ?? [])->pluck('composition.yarn_composition')->values() ?? null,
                'yarn_lot' => collect($item['knittingProgram']['yarnRequisition']['details'] ?? [])->pluck('yarn_lot'),
                'yarn_brand' => collect($item['knittingProgram']['yarnRequisition']['details'] ?? [])->pluck('yarn_brand'),
                'yarn_description' => collect($yarnDescription)->implode(', '),
            ];
        });
    }

}
