<?php

return [
    [
        'title' => 'Approval',
        'priority' => 1004,
        'url' => '',
        'icon' => '&#xe8e8;',
        'items' => [
            [
                'title' => 'User Approval Permission',
                'icon' => '',
                'url' => url('/approvals/user-approval-permission'),
            ],
            [
                'title' => 'Price Quotation',
                'icon' => '',
                'url' => url('/approvals/modules/price-quotation'),
            ],
            [
                'title' => 'Order',
                'icon' => '',
                'url' => url('/approvals/modules/order-approval'),
            ],
            [
                'title' => 'Budget',
                'icon' => '',
                'url' => url('/approvals/modules/budget'),
            ],
            [
                'title' => 'Po Approval',
                'icon' => '',
                'url' => url('/approvals/modules/poApproval'),
            ],
            [
                'title' => 'Fabric Booking Approval',
                'icon' => '',
                'url' => url('/approvals/modules/fabric-booking')
            ],
            [
                'title' => 'Short Fabric Booking Approval',
                'icon' => '',
                'url' => url('/approvals/modules/short-fabric-booking')
            ],
            [
                'title' => 'Trims Booking Approval',
                'icon' => '',
                'url' => url('/approvals/modules/trims-booking')
            ],
            [
                'title' => 'Short Trims Booking Approval',
                'icon' => '',
                'url' => url('/approvals/modules/short-trims-booking')
            ],
            [
                'title' => 'Service Booking Approval',
                'icon' => '',
                'url' => url('/approvals/modules/service-booking')
            ],
            [
                'title' => 'Embellishment Approval',
                'icon' => '',
                'url' => url('/approvals/modules/embellishment')
            ],
            [
                'title' => 'Yarn Purchase Approval',
                'icon' => '',
                'url' => url('/approvals/modules/yarn-purchase')
            ],
            [
                'title' => 'Gate Pass Challan Approval',
                'icon' => '',
                'url' => url('/approvals/modules/gate-pass-challan')
            ],
            [
                'title' => 'Print Send Challan(Cut Manager)',
                'icon' => '',
                'url' => url('/approvals/modules/print-send-challan-cut-manager')
            ],
            [
                'title' => 'Sewing Input Challan (Cut Manager)',
                'icon' => '',
                'url' => url('/approvals/modules/sewing-input-challan-cut-manager')
            ],
            [
                'title' => 'Cutting Qty Approval',
                'icon' => '',
                'url' => url('/approvals/modules/cutting-qty')
            ],
            [
                'title' => 'Inventory',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Yarn Store Approval',
                        'url' => url('/approvals/modules/yarn-store'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'Dyes Chemical Store Approval',
                        'url' => url('/approvals/modules/dyes-chemical-store'),
                        'icon' => '',
                    ],
                ]
            ],
        ],
    ],
];
