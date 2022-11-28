<?php

return [
    [
        'title' => 'SUBCONTRACT',
        'priority' => 6600,
        'url' => '',
        'icon' => '&#xe1bd;',
        'class' => 'nav-header hidden-folded',
        'items' => [],
    ],
    [
        'title' => 'Textile Subcontract',
        'priority' => 6601,
        'url' => '',
        'icon' => '&#xe1bd;',
        'items' => [
            [
                'title' => 'Order Management',
                'priority' => '',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Order Entry',
                        'icon' => '',
                        'url' => url('/subcontract/textile-orders/create'),
                    ],
                    [
                        'title' => 'Order List',
                        'icon' => '',
                        'url' => url('/subcontract/textile-orders'),
                    ],
                ],
            ],
            [
                'title' => 'Subcontract Grey Store',
                'priority' => '',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Material Receive',
                        'icon' => '',
                        'url' => url('/subcontract/material-fabric-receive'),
                    ],
                    [
                        'title' => 'Material Issue',
                        'icon' => '',
                        'url' => url('/subcontract/material-fabric-issue'),
                    ],
                    [
                        'title' => 'Material Transfer',
                        'icon' => '',
                        'url' => url('/subcontract/material-fabric-transfer'),
                    ],
                    [
                        'title' => 'Stock Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/sub-grey-store/stock-summery'),
                    ],
                ],
            ],
            [
                'title' => 'Dyeing Process',
                'priority' => '',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Batch Entry',
                        'icon' => '',
                        'url' => url('/subcontract/dyeing-process/batch-entry'),
                    ],
                    [
                        'title' => 'Recipe Entry',
                        'icon' => '',
                        'url' => url('/subcontract/dyeing-process/recipe-entry'),
                    ],
                    [
                        'title' => 'Multiple Recipe Download',
                        'icon' => '',
                        'url' => url('/subcontract/dyeing-process/multiple-recipe-download'),
                    ],
                    [
                        'title' => 'Requisition Entry',
                        'icon' => '',
                        'url' => url('/subcontract/dyeing-process/recipe-entry/requisition-entry'),
                    ],
                    [
                        'title' => 'Dyeing Production',
                        'icon' => '',
                        'url' => url('/subcontract/dyeing-production'),
                    ],
                    [
                        'title' => 'Dryer',
                        'icon' => '',
                        'url' => url('/subcontract/dryer'),
                    ],
                    [
                        'title' => 'Slitting',
                        'icon' => '',
                        'url' => url('/subcontract/slitting'),
                    ],
                    [
                        'title' => 'Stenter',
                        'icon' => '',
                        'url' => url('/subcontract/stenter'),
                    ],
                    [
                        'title' => 'Tumble',
                        'icon' => '',
                        'url' => url('/subcontract/tumbles'),
                    ],
                    [
                        'title' => 'Peach',
                        'icon' => '',
                        'url' => url('/subcontract/peaches'),
                    ],
                    [
                        'title' => 'Compactor',
                        'icon' => '',
                        'url' => url('/subcontract/compactor'),
                    ],
                    [
                        'title' => 'Brush',
                        'icon' => '',
                        'url' => url('/subcontract/finishing-productions'),
                    ],
                    [
                        'title' => 'Tube Compacting',
                        'icon' => '',
                        'url' => url('/subcontract/tube-compacting'),
                    ],
                    [
                        'title' => 'Squeezer',
                        'icon' => '',
                        'url' => url('/subcontract/squeezer'),
                    ],
                    [
                        'title' => 'HT Set',
                        'icon' => '',
                        'url' => url('/subcontract/ht-set'),
                    ],
                ],
            ],
            [
                'title' => 'Dyeing Delivery and Billing',
                'priority' => '',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sub Goods Delivery',
                        'icon' => '',
                        'url' => url('/subcontract/sub-dyeing-goods-delivery'),
                    ],
                    [
                        'title' => 'Sub In Bound Billing',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                ],
            ],
            [
                'title' => 'Reports',
                'priority' => '',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Dyeing Production Date Wise Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/dyeing-production/date-wise'),
                    ],
                    [
                        'title' => 'Party And Order Wise Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/party-order'),
                    ],
                    [
                        'title' => 'Batch Tracking Status',
                        'icon' => '',
                        'url' => url('/subcontract/report/batch'),
                    ],
                    [
                        'title' => 'Order Tracking Status',
                        'icon' => '',
                        'url' => url('/subcontract/report/order'),
                    ],
                    [
                        'title' => 'Order Profit Loss Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/order/profit-loss'),
                    ],
                    [
                        'title' => 'Daily Dyeing Production Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/dyeing-production/daily'),
                    ],
                    [
                        'title' => 'Daily Finishing Production Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/finishing-production/daily'),
                    ],
                    [
                        'title' => 'Dyeing Batch Costing Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/batch/costing'),
                    ],
                    [
                        'title' => 'Dyes & Chemical Costing Statement',
                        'icon' => '',
                        'url' => url('/subcontract/report/dyes-chemical/costing'),
                    ],
                    [
                        'title' => 'Challan Wise Receive Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/dyeing-ledger-report'),
                    ],
                    [
                        'title' => 'Order Wise Stock Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/order-wise-stock-report'),
                    ],
                    [
                        'title' => 'Date Wise Delivery Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/date-wise-delivery-report'),
                    ],
                    [
                        'title' => 'Grey Fabric Stock Summary Report',
                        'icon' => '',
                        'url' => url('/subcontract/report/grey-fabric-stock-summary'),
                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'SubContract Variables',
        'priority' => 6602,
        'url' => '',
        'icon' => '&#xe1bd;',
        'items' => [
            [
                'title' => 'SubContract Variable Settings',
                'icon' => '',
                'url' => url('/subcontract/sub-dyeing-variable'),
            ],
        ],
    ],

];
