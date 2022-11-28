<?php


namespace SkylarkSoft\GoRMG\Commercial\Constants;

class CommercialConstant
{
    const ExportProceedDeductionStatus = 1;
    const ExportProceedDistributionStatus = 2;

    const AccountHeadOptions = [
        '1' => 'Add Confirmation Change',
        '2' => 'Advance A/C',
        '3' => 'Application Form Fee',
        '4' => 'BTB Margin/DFC/BLO A/C',
        '5' => 'Bank Charge',
        '6' => 'Bank Commission',
        '7' => 'Bi-Salam/PC',
        '8' => 'CC Account',
        '9' => 'CD Account',
        '10' => 'Cash Security A/C',
        '11' => 'Courier Charge',
        '12' => 'Discount A/C',
        '13' => 'EDF A/C',
        '14' => 'ERQ A/C',
        '15' => 'Excise Duty',
        '16' => 'Export Cash Credit',
        '17' => 'FDBC Commission',
        '18' => 'FDR Build up',
        '19' => 'FTT/TR',
        '20' => 'Force Loan',
        '21' => 'Foreign Collection Charge',
        '22' => 'Foreign Commission',
        '23' => 'HPSM',
        '24' => 'Handling Charge',
        '25' => 'IFDBC Liability',
        '26' => 'Import Margin A/C',
        '27' => 'Insurance Coverage',
        '28' => 'Interest',
        '29' => 'LIM',
        '30' => 'LTR',
        '31' => 'Loan A/C',
        '32' => 'Local  Commission',
        '33' => 'MDA Normal',
        '34' => 'MDA Special',
        '35' => 'MDA UR',
        '36' => 'Miscellaneous Charge',
        '37' => 'Negotiation Loan/Liability',
        '38' => 'OD A/C20',
        '39' => 'Other Charge',
        '40' => 'PAD',
        '41' => 'Packing Credit',
        '42' => 'Penalty on Doc Discrepancy',
        '44' => 'Penalty on Goods Discrepancy',
        '45' => 'Postage Charge',
        '46' => 'STD A/C',
        '47' => 'SWIFT Charge',
        '48' => 'Settlement A/C',
        '49' => 'Source Tax',
        '50' => 'Sundry A/C',
        '51' => 'Telex Charge',
        '52' => 'Term Loan',
        '53' => 'Vat',
        '54' => 'Vat On Bank Commission',
        '55' => 'others Fund(sinking)',
    ];

    const LC_TYPES = [
        1 => 'BTB LC',
        2 => 'Margin LC',
    ];

    const DELIVERY_MODE = [
        1 => 'Sea',
        2 => 'Air',
        3 => 'Road',
        4 => 'Road/Air',
    ];

    const PAY_HEADS = [
        1 => 'Bank Commission',
        2 => 'Vat On Bank Commission',
        3 => 'Insurance Coverage',
        4 => 'Add Confirmation Change',
    ];

    const CHARGES_FOR = [
        1 => 'LC Opening',
        2 => 'LC Amendments',
    ];

    const BTB_LC_TYPE = 1;
    const MARGIN_LC_TYPE = 2;

    const RETIRE_SOURCES = [
        1 => 'BTB Margin/DFC/BLO A/C',
        2 => 'ERQ A/C',
        3 => 'CD Account',
        4 => 'STD A/C',
        5 => 'CC Account',
        6 => 'OD A/C20',
        7 => 'EDF A/C',
        8 => 'PAD',
        9 => 'LTR',
        10 => 'FTT',
        11 => 'TR',
        12 => 'LIM',
        13 => 'Term Loan',
        14 => 'Import Margin A/C',
    ];

    const ACCEPTANCE_TIME = [
        1 => ' After Goods Receive',
        2 => 'Before Goods Receive',
    ];

    const DBP_TYPES = [
        '1' => 'LDBC',
        '2' => 'FDBC',
        '3' => 'TT-Foreign',
        '4' => 'TT-Local'
    ];

    const SOURCES = [
        '1' => 'EPZ',
        '3' => 'Non-EPZ',
        '4' => 'Local',
        '5' => 'Foreign'
    ];

}
