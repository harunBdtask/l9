<?php


return [
    [
        'title' => 'General Inventory',
        'priority' => 4004,
        'icon' => '',
        'url' => '',
        'items' => [
            [
                'title' => 'Stock In',
                'icon' => '',
                'url' => url('/general-store/stores/1/in'),
                'items' => []
            ],
            [
                'title' => 'Stock Out',
                'icon' => '',
                'url' => url('/general-store/stores/1/out'),
                'items' => []
            ],
            [
                'title' => 'Vouchers',
                'icon' => '',
                'url' => url('/general-store/vouchers/1'),
                'items' => []
            ],
            [
                'title' => 'Stock Summary Report',
                'icon' => '',
                'url' => url('/general-store/stores/1/report'),
                'items' => []
            ],
            [
                'title' => 'CAT Wise Stock Summary Report',
                'icon' => '',
                'url' => url('/general-store/stores/1/report2'),
                'items' => []
            ],
            [
                'title' => 'Item Wise Stock Summary Report',
                'icon' => '',
                'url' => url('/general-store/stores/1/item-wise-summery'),
                'items' => []
            ],
            [
                'title' => 'Daily Report',
                'icon' => '',
                'url' => url('/general-store/stores/daily/1/report'),
                'items' => []
            ],
        ]
    ],
];
