<?php


namespace SkylarkSoft\GoRMG\Commercial;

class Options
{
    const CURRENCIES = [
        'usd' => 'USD',
        'taka' => 'TAKA',
        'euro' => "EURO",
        'chf' => 'CHF',
        'sdg' => 'SDG',
    ];

    const CONVERTIBLE_TO = [
        '' => 'Select',
        'lc/sc' => 'LC/SC',
        'no' => 'No',
        'finance' => 'Finacne',
    ];

    const BANKS = [
        '' => 'Select Bank',
        1 => 'Brac Bank',
        2 => 'UCB Bank',
        3 => 'City Bank',
    ];

    const SHIPPING_MODES = [
        'sea' => 'Sea',
        'air' => 'Air',
        'road' => 'Road',
        'train' => 'Train',
        'sea/air' => 'Sea/Air',
        'courier' => 'Courier',
        'sea/air/road' => 'Sea/Air/Road',
    ];

    const PAY_TERMS = [
        'at_sight' => 'At Sight',
        'usnace' => 'Usance',
        'cash_in_advance' => 'Cash in Advance',
        'open_account' => 'Open Account',
        'block_order' => 'Block Order',
        'deferred' => 'Deferred',
    ];

    const INCO_TERMS = [
        'fob' => 'FOB',
        'cfr' => 'CFR',
        'cif' => 'CIF',
        'fca' => 'FCA',
        'cpt' => 'CPT',
        'exw' => 'EXW',
        'fas' => 'FAS',
        'cip' => 'CIP',
        'daf' => 'DAF',
        'des' => 'DES',
    ];

    const CONTACT_SOURCES = [
        'foreign' => 'FOREIGN',
        'inland' => 'INLAND',
    ];

    const EXPORT_ITEM_CATEGORIES = [
        'knit_garments' => 'Knit Garments',
        'woven_garments' => 'Woven Garments',
        'sweater_garments' => 'Sweater Garments',
        'leather_garments' => 'Leather Garments',
        'knit_fabric' => 'Knit Fabric',
        'woven_fabric' => 'Woven Fabric',
        'knitting' => 'Knitting',
        'weaving' => 'Weaving',
        'all_over_printing' => 'All Over Printing',
        'fabric_washing' => 'Fabric Washing',
        'cutting' => 'Cutting',
        'sewing' => 'Sewing',
        'garments_printing' => 'Garments Printing',
        'garments_embroidery' => 'Garments Embroidery',
        'garments_washing' => 'Garments Washing',
        'yarn' => 'Yarn',
        'trims' => 'Trims',
        'chemical' => 'Chemical',
        'dyes' => 'Dyes',
        'food_item' => 'Food Item',
        'medicine' => 'Medicine',
        'transportation' => 'Transportation',
        'c_&_f' => 'C & F',
    ];

    const TRANSFERABLE = [
        'yes' => 'Yes',
        'no' => 'No',
    ];

    const REPLACEMENT_LC = [
        'no' => 'No',
        'yes' => 'Yes',
    ];

    const VARIABLE_NAME = [
        '' => 'Select',
        'btb_limit_percent' => 'BTB Limit Percent',
        'max_pc_limit' => 'Max PC Limit',
    ];

    const CHANGED_BY = [
        'increase' => 'Increase',
        'decrease' => 'Decrease',
    ];
}
