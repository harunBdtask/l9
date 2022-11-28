<?php //knitting menu

return [
    [
        'title' => 'TEXTILE PRODUCTION',
        'priority' => 2100,
        'url' => '',
        'icon' => '&#xe1bd;',
        'class' => 'nav-header hidden-folded',
        'items' => [],
    ],
    [
        'title' => 'Knitting',
        'priority' => 2101,
        'url' => '',
        'icon' => '&#xe80e;',
        'items' => [
            [
                'title' => 'Fabric Booking List',
                'url' => url('/knitting/fabric-booking-list'),
                'icon' => '',
                'items' => []
            ],
//            [
//                'title' => 'Yarn Allocation',
//                'url' => url('/knitting/yarn-allocation'),
//                'icon' => '',
//                'items' => []
//            ],
            [
                'title' => 'Fabric Sales Order',
                'url' => url('/knitting/fabric-sales-order'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Planning Info Entry',
                'url' => url('/knitting/planning-info-entry'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Program List',
                'url' => url('/knitting/program-list'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Yarn Requisition',
                'url' => url('/knitting/yarn-requisition'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Yarn Requisition List',
                'url' => url('/knitting/yarn-requisition-list'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Knit Card',
                'url' => url('/knitting/knit-card'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Roll List',
                'url' => url('/knitting/knitting-roll'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Production Planning',
                'url' => url('/knitting/knit-card/production-planning'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Floor Planning',
                'url' => url('/knitting/knit-card/floor-planning'),
                'icon' => '',
                'items' => []
            ],
            /*[ //Same as Knit card List
                'title' => 'Knitting Production',
                'url' => url('/knitting/knitting-production'),
                'icon' => '',
                'items' => []
            ],*/
            [
                'title' => 'Knitting QC',
                'url' => url('/knitting/knitting-qc'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Roll wise fabric delivery',
                'url' => url('/knitting/roll-wise-fabric-delivery'),
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Reports',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Daily Production Report',
                        'url' => url('/knitting/daily-production-report'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'Daily Program Report',
                        'url' => url('/knitting/daily-knitting-report'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'Buyer Style Report',
                        'url' => url('/knitting/buyer-style-report'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'Order Status Report',
                        'url' => url('/knitting/order-status-report'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'Yarn Allocation Report',
                        'url' => url('/knitting/yarn-allocation-report'),
                        'icon' => '',
                    ],
                ]
            ],
        ]
    ],

];
