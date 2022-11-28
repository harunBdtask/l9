<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\GreyDelivery;

class GreyDeliveryFormatterService
{
    public static function formatDetails($item): array
    {
        $id = $item['id'];
        return [
            'id' => $id,
            'grey_receive_id' => $item['receiveDetail']['grey_receive_id'],
            'factory_id' => $item['receiveDetail']['factory_id'],
            'knitting_program_id' => $item['receiveDetail']['knitting_program_id'],
            'plan_info_id' => $item['receiveDetail']['plan_info_id'],
            'knitting_program_roll_id' => $item['receiveDetail']['knitting_program_roll_id'],
            'yarn_composition_id' => $item['receiveDetail']['yarn_composition_id'],
            'yarn_count_id' => $item['receiveDetail']['yarn_count_id'],
            'factory_name' => $item['receiveDetail']['factory_name'],
            'book_company' => $item['receiveDetail']['book_company'],
            'knitting_source_value' => $item['receiveDetail']['knitting_source_value'],
            'buyer_name' => $item['receiveDetail']['buyer_name'],
            'style_name' => $item['receiveDetail']['style_name'],
            'unique_id' => $item['receiveDetail']['unique_id'],
            'po_no' => $item['receiveDetail']['po_no'],
            'booking_no' => $item['receiveDetail']['booking_no'],
            'body_part' => $item['receiveDetail']['body_part'],
            'body_part_name' => $item['receiveDetail']['bodyPartData']['name'],
            'color_type' => $item['receiveDetail']['color_type'],
            'color_type_name' => $item['receiveDetail']['colorTypeData']['color_types'],
            'fabric_description' => $item['receiveDetail']['fabric_description'],
            'item_color' => $item['receiveDetail']['item_color'],
            'program_no' => $item['receiveDetail']['program_no'],
            'pcs_production_qty' => $item['receiveDetail']['pcs_production_qty'],
            'scanable_barcode' => $item['receiveDetail']['scanable_barcode'],
            'yarn_lot' => $item['receiveDetail']['yarn_lot'],
            'yarn_count_value' => $item['receiveDetail']['yarn_count_value'],
            'yarn_composition_value' => $item['receiveDetail']['yarn_composition_value'],
            'yarn_brand' => $item['receiveDetail']['yarn_brand'],
            'delivery_status' => $item['receiveDetail']['delivery_status'],
            'yarn_description' => $item['receiveDetail']['yarn_description'],
        ];
    }

}
