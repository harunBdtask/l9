<?php

return [
    [
        'title' => 'M/C INVENTORY',
        'priority' => 5500,
        'url' => '',
        'icon' => '&#xe1bd;',
        'class' => 'nav-header hidden-folded',
        'items' => []
    ],
    [
        'title' => 'M/C Settings',
        'priority' => 5501,
        'url' => '',
        'icon' => '&#xe89e;',
        'items' => [
            [
                'title' => 'M/C Location',
                'icon' => '',
                'url' => url('/mc-inventory/machine-location'),
            ],
            [
                'title' => 'M/C Type',
                'icon' => '',
                'url' => url('/mc-inventory/machine-type'),
            ],
            [
                'title' => 'M/C Sub-Type',
                'icon' => '',
                'url' => url('/mc-inventory/machine-sub-type'),
            ],
            [
                'title' => 'M/C Brand',
                'icon' => '',
                'url' => url('/mc-inventory/machine-brand'),
            ],
            [
                'title' => 'M/C Unit',
                'icon' => '',
                'url' => url('/mc-inventory/machine-unit'),
            ],

        ],
    ],
    [
        'title' => 'Machine Modules',
        'priority' => 5503,
        'url' => '',
        'icon' => '&#xe89d;',
        'items' => [
            [
                'title' => 'Barcode Generation',
                'icon' => '',
                'url' => url('/mc-inventory/machine-barcode-generation'),
            ],
            [
                'title' => 'Machine Profile',
                'icon' => '',
                'url' => url('/mc-inventory/machine-profile'),
            ],
            [
                'title' => 'Maintenance Calender',
                'icon' => '',
                'url' => url('/mc-inventory/maintenance-calender'),
            ],
            [
                'title' => 'Maintenance',
                'icon' => '',
                'url' => url('/mc-inventory/maintenance'),
            ],
            [
                'title' => 'Machine Transfer',
                'icon' => '',
                'url' => url('/mc-inventory/machine-transfer'),
            ],
            [
                'title' => 'Machine Dashboard',
                'icon' => '',
                'url' => url('/mc-inventory/machine-dashboard'),
            ],
            [
                'title' => 'Inventory Chart Format',
                'icon' => '',
                'url' => url('/mc-inventory/inventory-chart-format'),
            ],

        ],
    ],
];
