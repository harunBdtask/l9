<?php


// sample menu

return [
    [
        'title' => 'INVENTORY',
        'priority' => 4000,
        'url' => '',
        'icon' => '&#xe14e;',
        'class' => 'nav-header hidden-folded',
        'items' => []
    ],
//    [
//        'title' => 'Trims Store',
//        'priority' => 4001,
//        'url' => '',
//        'icon' => '&#xe8ea;',
//        'items' => [
//            [
//                'title' => 'Trims Receive',
//                'icon' => '',
//                'url' => url('/inventory/trims-receives')
//            ],
//            [
//                'title' => 'Trims Receive Return',
//                'icon' => '',
//                'url' => url('/inventory/trims-receive-returns')
//            ],
//            [
//                'title' => 'Trims Issue',
//                'icon' => '',
//                'url' => url('/inventory/trims-issue/list')
//            ],
//            [
//                'title' => 'Trims Issue Return',
//                'icon' => '',
//                'url' => url('/inventory/trims-issue-return')
//            ],
//            [
//                'title' => 'Trims Order To Order Transfer',
//                'icon' => '',
//                'url' => url('/inventory/trims-order-transfer'),
//            ],
//            [
//                'title' => 'Sample',
//                'icon' => '',
//                'url' => url('/sample'),
//            ],
//        ],
//    ],
    [
        'title' => 'Trims Store',
        'priority' => 4002,
        'url' => '',
        'icon' => '&#xe8ea;',
        'items' => [
            [
                'title' => 'Trims Inventory',
                'icon' => '',
                'url' => url('/inventory/trims-store/inventory')
            ],
            [
                'title' => 'Trims Receive',
                'icon' => '',
                'url' => url('/inventory/trims-store/receive')
            ],
            [
                'title' => 'MRR',
                'icon' => '',
                'url' => url('/inventory/trims-store/mrr')
            ],
            [
                'title' => 'Bin Card',
                'icon' => '',
                'url' => url('/inventory/trims-store/bin-card')
            ],
            [
                'title' => 'Trims Issue',
                'icon' => '',
                'url' => url('/inventory/trims-store/issue'),
            ],
            [
                'title' => 'Trims Delivery Challan',
                'icon' => '',
                'url' => url('/inventory/trims-store/delivery-challan'),
            ],
            [
                'title' => 'Sample Trims Issue',
                'icon' => '',
                'url' => url('/sample-management/trims-issue/list'),
            ],
            [
                'title' => 'Reports',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Daily Details Report',
                        'icon' => '',
                        'url' => url('/inventory/trims-store/reports/daily-details-report')
                    ],
                    [
                        'title' => 'Monthly Stock Up Report',
                        'icon' => '',
                        'url' => url('/inventory/trims-store/reports/monthly-stock-up-report')
                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'Trims Store Pro',
        'priority' => 4003,
        'url' => '',
        'icon' => '&#xe8ea;',
        'items' => [
            [
                'title' => 'Trims Receive',
                'icon' => '',
                'url' => url('/trims-store/receive')
            ],
            [
                'title' => 'Trims Receive Return',
                'icon' => '',
                'url' => url('/trims-store/receive-return')
            ],
            [
                'title' => 'Trims Issue',
                'icon' => '',
                'url' => url('/trims-store/issues')
            ],
            [
                'title' => 'Trims Issue Return',
                'icon' => '',
                'url' => url('/trims-store/issue-return')
            ],
        ],
    ],
    [
        'title' => 'Yarn Store',
        'priority' => 4004,
        'url' => '',
        'icon' => '&#xe42b;',
        'items' => [
            [
                'title' => 'Yarn Receive',
                'icon' => '',
                'url' => url('/inventory/yarn-receive')
            ],
            [
                'title' => 'Yarn Receive Return',
                'icon' => '',
                'url' => url('/inventory/yarn-receive-return')
            ],
            [
                'title' => 'Yarn Issue',
                'icon' => '',
                'url' => url('/inventory/yarn-issue')
            ],
            [
                'title' => 'Yarn Issue Return',
                'icon' => '',
                'url' => url('/inventory/yarn-issue-return')
            ],
            /*[ // Under Construction
                'title' => 'Yarn Transfer',
                'icon' => '',
                'url' => '#' //url('/inventory/yarn-transfer')
            ],*/
            [
                'title' => 'Yarn Ledger',
                'icon' => '',
                'url' => url('/inventory/yarn-item-ledger')
            ],
            [
                'title' => 'Yarn Gate Pass Scan',
                'icon' => '',
                'url' => url('/inventory/yarn-gate-pass-challan-scan')
            ],
            [
                'title' => 'Yarn Gate Pass Scan List',
                'icon' => '',
                'url' => url('/inventory/yarn-gate-pass-challan-scan/show')
            ],
            [
                'title' => 'Reports',
                'url' => '',
                'icon' => '',
                'items' => [
//                    [
//                        'title' => 'Good Received With LC Open',
//                        'icon' => '',
//                        'url' => url('/inventory/good-received-with-lc-open')
//                    ],
                    [
                        'title' => 'Daily Yarn Receive Statement',
                        'icon' => '',
                        'url' => url('/inventory/daily-yarn-receive-statement')
                    ],
                    [
                        'title' => 'Goods Received Without LC (BETA)',
                        'icon' => '',
                        'url' => url('/inventory/yarn-store/goods-receive-without-lc')
                    ],
                    [
                        'title' => 'Good Received With LC Open (BETA)',
                        'icon' => '',
                        'url' => url('/inventory/yarn-store/goods-receive-with-lc')
                    ],
                    [
                        'title' => 'Challan wise Receive Statement',
                        'icon' => '',
                        'url' => url('/inventory/challan-wise-receive-statement')
                    ],
                    [
                        'title' => 'Daily Yarn Issue Statement',
                        'icon' => '',
                        'url' => url('/inventory/daily-yarn-issue-report')
                    ],
                    [
                        'title' => 'Yarn Stock Summary',
                        'icon' => '',
                        'url' => url('/inventory/yarn-stock-summary-report')
                    ],
                    [
                        'title' => 'Yarn Stock Summary Supplier-Lot Wise',
                        'icon' => '',
                        'url' => url('/inventory/yarn-stock-summary-supplier-lot-wise-report')
                    ],
                ]
            ],
        ],
    ],
    [
        'title' => 'Grey Fabric Store',
        'priority' => 4005,
        'url' => '',
        'icon' => '&#xe8f0;',
        'items' => [
            [
                'title' => 'Grey Receive',
                'icon' => '',
                'url' => url('/inventory/grey-receive')
            ],
            [
                'title' => 'Grey Delivery',
                'icon' => '',
                'url' => url('/inventory/grey-delivery')
            ],
        ]
    ],
    // priority 4004 => modules/general-store/menu/menu.php > General Inventory
    // priority 4005 => modules/dyes-store/menu/menu.php > Dyes & Chemical Store
    [
        'title' => 'Finish Fabrics Store',
        'priority' => 4007,
        'url' => '',
        'icon' => '&#xe42a;',
        'items' => [
            [
                'title' => 'Fabric Receives',
                'icon' => '',
                'url' => url('/inventory/fabric-receives')
            ],
            [
                'title' => 'Fabric Receive Returns',
                'icon' => '',
                'url' => url('/inventory/fabric-receive-returns')
            ],
            [
                'title' => 'Fabric Issues',
                'icon' => '',
                'url' => url('/inventory/fabric-issues')
            ],
            [
                'title' => 'Fabric Issue Return',
                'icon' => '',
                'url' => url('/inventory/fabric-issue-returns')
            ],
            [
                'title' => 'Fabric Transfers',
                'icon' => '',
                'url' => url('/inventory/fabric-transfers')
            ],
            [
                'title' => 'Reports',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Finish Fabric Receive Report',
                        'icon' => '',
                        'url' => url('/inventory/finish-fabric-receive-report')
                    ],
                    [
                        'title' => 'Finish Fabric Issue Report',
                        'icon' => '',
                        'url' => url('/inventory/finish-fabric-issue-report')
                    ],
                    [
                        'title' => 'Finish Fabric Stock Report',
                        'icon' => '',
                        'url' => url('/inventory/finish-fabric-monthly-stock-report')
                    ],
                ]
            ],
        ]
    ],
    [
        'title' => 'Inventory Reports',
        'priority' => 4008,
        'url' => '',
        'icon' => '&#xe8ed;',
        'items' => [
            [
                'title' => 'Finish Fabric Store Report',
                'icon' => '',
                'url' => url('/inventory/finish-fabric-report'),
            ],
            [
                'title' => 'Fabric Stock Summary Report',
                'icon' => '',
                'url' => url('/inventory/fabric-stock-summery-report'),
            ],
        ]
    ],
    [
        'title' => 'Warehouse Management',
        'priority' => 4009,
        'url' => '',
        'icon' => '&#xe0af;',
        'items' => [
            [
                'title' => 'Warehouse Floors',
                'icon' => '',
                'url' => url('warehouse-floors'),
            ],
            [
                'title' => 'Warehouse Racks',
                'icon' => '',
                'url' => url('warehouse-racks'),
            ],
            [
                'title' => 'Warehouse Cartons',
                'icon' => '',
                'url' => url('warehouse-cartons'),
            ],
            [
                'title' => 'Carton Allocation',
                'icon' => '',
                'url' => url('warehouse-carton-allocation'),
            ],
            [
                'title' => 'Shipment Scan',
                'icon' => '',
                'url' => url('warehouse-shipment-scan'),
            ],
            [
                'title' => 'Shipment Challans',
                'icon' => '',
                'url' => url('warehouse-shipment-challans'),
            ],
            [
                'title' => 'Reports',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Daily In Report',
                        'icon' => '',
                        'url' => url('warehouse-daily-in-report'),
                    ],
                    [
                        'title' => 'Daily Out Report',
                        'icon' => '',
                        'url' => url('warehouse-daily-out-report'),
                    ],
                    [
                        'title' => 'Floor Wise Status Report',
                        'icon' => '',
                        'url' => url('warehouse-floor-wise-status-report'),
                    ],
                    [
                        'title' => 'Buyer Style Wise Status Report',
                        'icon' => '',
                        'url' => url('warehouse-buyer-style-wise-status-report'),
                    ],
                    [
                        'title' => 'Carton Scan Check',
                        'icon' => '',
                        'url' => url('warehouse-scan-barcode-check'),
                    ],
                ],
            ],
        ],
    ],
];
