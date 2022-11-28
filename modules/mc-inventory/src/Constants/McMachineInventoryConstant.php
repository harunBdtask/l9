<?php

namespace SkylarkSoft\GoRMG\McInventory\Constants;

class McMachineInventoryConstant
{
    const LOCATION_TYPES = [
        1 => 'INHOUSE',
        2 => 'LOAN',
        3 => 'OUTSIDE'
    ];

    const MACHINE_CATEGORIES = [
        1 => 'SEWING',
        2 => 'CUTTING',
        3 => 'FINISHING'
    ];

    const MACHINE_ORIGINS = [
        1 => 'Loan',
        2 => 'Purchase',
        3 => 'Rental',
        0 => 'N/A',
    ];

    const MACHINE_TENORS = [
       30,
       45,
       60,
       90,
       120,
       180
    ];

    const MACHINE_STATUS = [
        1 => 'Active',
        2 => 'Inactive',
        3 => 'Sold Out',
        4 => 'Disposed',
        5 => 'Idle'
    ];

    const MAINTENANCE_STATUS = [
        1 => 'Done',
        0 => 'Not Done'
    ];

    const YES_NO = [
        1 => 'Yes',
        0 => 'No'
    ];
}
