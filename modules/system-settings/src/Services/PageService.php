<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class PageService
{

    const ORDER_ENTRY = 'order_entry';
    const PRICE_QUOTATION = 'price_quotation';
    const FABRIC_BOOKING = 'fabric_booking';
    const SALES_CONTRACT = 'sales_contract';
    const PURCHASE_ORDER = 'purchase_order';
    const PROFORMA_INVOICE = 'proforma_invoice';
    const BTB_MARGIN_LC = 'btb_margin_lc';

    public static function pages(): array
    {
        return [
            self::ORDER_ENTRY => [
                'name' => 'Order Entry',
                'fields' => [
                    'style_description' => 'Style Description',
                    'product_category' => 'Product Category',
                    'product_department' => 'Product Department',
                    'fabrication' => 'Fabrication',
                    'region' => 'Region',
                    'team_leader' => 'Team Leader',
                    'dealing_merchant' => 'Dealing Merchant',
                    'factory_merchant' => 'Factory Merchant',
                    'season' => 'Season',
                    'ship_mode' => 'Ship Mode',
                    'packing_ratio' => 'Packing Ratio',
                    'currency' => 'Currency',
                    'repeat_no' => 'Repeat No',
                    'buying_agent' => 'Buying Agent',
                    'quality_label' => 'Quality Label',
                    'style_owner' => 'Style Owner',
                    'client' => 'Client',
                    'assigning_factory' => 'Assigning Factory',
                    'remarks' => 'Remarks',
                    'images' => 'Images',
                    'reference_no' => 'Reference No',
                    'garments_item_group' => 'Garments Item Group',
                    'fabric_composition' => 'Fabric Composition',
                    'fabric_type' => 'Fabric Type',
                    'sub_department' => 'Sub Department',
                    'pcs_per_carton' => 'PCS/Carton',
                    'cbm_per_carton' => 'CBM/Carton',
                    'combo' => 'Combo',
                    'sustainable_material' => 'Sustainable Material',
                ]
            ],
            self::PURCHASE_ORDER => [
                'name' => 'Purchase Order',
                'fields' => [
                    'carton_info' => 'Carton Info',
                    'internal_ref_no' => 'Internal Ref No',
                    'delay_for' => 'Delay For',
                    'customer' => 'Customer',
                    'remarks' => 'Remarks',
                    'country_code' => 'Code',
                    'area' => 'Area',
                    'area_code' => 'Area Code',
                    'pack_type' => 'Pack Type',
                    'required_hanger' => 'Req. Hanger',
                    'po_receive_date' => 'PO Receive Date',
                    'common_file' => 'Common File',
                    'packing_ratio' => 'Packing Ratio',
                    'cut_off' => 'Cut Off',
                    'cut_off_date' => 'Cut Off Date',
                    'country_ship_date' => 'Country Ship Date',
                    'pcs_per_pack' => 'Pcs Per Pack',
                    //'factory_fob' => 'Fac.FOB'
                ]
            ],
            self::PROFORMA_INVOICE => [
                'name' => 'Proforma Invoice',
                'fields' => [
                    'pi_validity_date' => 'PI Validity Date',
                    'indentor_name' => 'Indentor Name',
                    'lc_group_no' => 'LC Group No',
                    'lc_receive_date' => 'LC Receive Date',
                    'pay_term' => 'Pay Term',
                    'tenor' => 'Tenor',
                    'beneficiary' => 'Beneficiary',
                    'last_shipment_date' => 'Last Shipment Date',
                    'pi_for' => 'PI For',
                    'priority' => 'Priority',
                    'ready_to_approve' => 'Ready To Approve',
                    'approval_user_id' => 'Approval User',
                    'country_id' => 'Origin',
                ]
            ],
            self::BTB_MARGIN_LC => [
                'name' => 'BTB Margin LC',
                'fields' => [
                    'inco_term' => 'Inco Term',
                    'inco_term_place' => 'Inco Term Place',
                    'pay_term' => '	Pay Term',
                    'tolerance_percentage' => 'Tolerance %',
                    'delivery_mode' => 'Delivery Mode',
                    'doc_present_days' => 'Doc Present Days',
                    'port_of_loading' => 'Port of Loading',
                    'port_of_discharge' => 'Port of Discharge',
                    'etd_date' => 'ETD Date',
                    'lca_no' => 'LCA No',
                    'lcaf_no' => 'LCAF No',
                    'imp_form_no' => 'IMP Form No',
                    'insurance_company' => 'Insurance Company',
                    'cover_note_no' => 'Cover Note No',
                    'cover_note_date' => 'Cover Note Date',
                    'psi_company' => 'PSI Company',
                    'maturity_from' => 'Maturity From',
                    'margin_deposite_percentage' => 'Margin Deposit %',
                    'origin' => 'Origin',
                    'shipping_mark' => 'Shipping Mark',
                    'ud_no' => 'UD No',
                    'ud_date' => 'UD Date',
                    'credit_to_be_advised' => 'Credit To Be Advised',
                    'add_confirming_bank' => 'Add Confirming Bank',
                    'bonded_warehouse' => 'Bonded Warehouse',
                    'remarks' => 'Remarks'
                ]
            ],
            self::FABRIC_BOOKING => [
                'name' => 'Fabric Booking',
                'fields' => [
                    'sample_fabric_qty' => 'Sample Fabric Quantity'
                ]
            ]
        ];
    }
}
