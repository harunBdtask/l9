<?php

// sample menu

return [
    [
        'title' => 'CRM',
        'priority' => 1000,
        'url' => '',
        'icon' => '&#xe8b8;',
        'class' => 'nav-header hidden-folded',
        'items' => [],
    ],
    [
        'title' => 'Marketing',
        'priority' => 1001,
        'url' => '',
        'icon' => '&#xe85b;',
        'items' => [
            [
                'title' => 'Sales Target Determination',
                'icon' => '',
                'url' => url('/sales-target-determination'),
            ],
            [
                'title' => 'Quotation Inquiry',
                'icon' => '',
                'url' => url('/quotation-inquiries'),
            ],
            [
                'title' => 'Price Quotation',
                'icon' => '',
                'url' => url('/price-quotations'),
            ],
        ],
    ],
    [
        'title' => 'Sample',
        'priority' => 1002,
        'url' => '',
        'icon' => '&#xe8b5;',
        'items' => [
            [
                'title' => 'Sample Order',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sample Requisition',
                        'icon' => '',
                        'url' => url('/sample-management/order-requisition/list'),
                    ]
                ],
            ],
            [
                'title' => 'Sample Info',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sample TNA',
                        'icon' => '',
                        'url' => url('/sample-management/sample-tna/list'),
                    ],
                    [
                        'title' => 'Sample Processing',
                        'icon' => '',
                        'url' => url('/sample-management/sample-processing/list'),
                    ],
                ],
            ],
            [
                'title' => 'Sample Store',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sample Trims Receive',
                        'icon' => '',
                        'url' => url('/sample-management/trims-receive/list'),
                    ],
                    [
                        'title' => 'Finish Fabric Receive Return',
                        'icon' => '',
                        'url' => url('/inventory/fabric-receive-returns'),
                    ],
                    [
                        'title' => 'Accessories Receive',
                        'icon' => '',
                        'url' => url('/inventory/trims-receives'),
                    ],
                    [
                        'title' => 'Order Transfer',
                        'icon' => '',
                        'url' => url('/inventory/fabric-transfers'),
                    ],
                    [
                        'title' => 'GMTS Leftover Send',
                        'icon' => '',
                        'url' => url('/sample-info-processing-entry'),
                    ],
                    [
                        'title' => 'Finish Fabric Leftover Send',
                        'icon' => '',
                        'url' => url('/sample-info-processing-entry'),
                    ],
                ],
            ],
            [
                'title' => 'Sample Reports',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Daily Sample Reports',
                        'icon' => '',
                        'url' => url('/sample-info-tna'),
                    ],
                    [
                        'title' => 'Sample TNA Reports',
                        'icon' => '',
                        'url' => url('/sample-info-processing-entry'),
                    ],
                    [
                        'title' => 'Sample Recap Reports',
                        'icon' => '',
                        'url' => url('/sample-info-processing-entry-report'),
                    ],
                    [
                        'title' => 'GMTS Lftover Reports',
                        'icon' => '',
                        'url' => url('sample-management/sample-info-processing-entry'),
                    ],
                    [
                        'title' => 'Fin Fabric Lftover Reports',
                        'icon' => '',
                        'url' => url('sample-management/sample-info/sample-info-processing-entry'),
                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'Merchandising',
        'priority' => 1003,
        'url' => '',
        'icon' => '&#xe3e8;',
        'items' => [
            [
                'title' => 'Order/Style Tracking',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Order/Style Entry',
                        'icon' => '',
                        'url' => url('/orders'),
                    ],
                    [
                        'title' => 'Budget/Costing',
                        'icon' => '',
                        'url' => url('/budgets'),
                    ],
                    [
                        'title' => 'Sample List',
                        'icon' => '',
                        'url' => url('/samples'),
                    ],
                ],
            ],

            [
                'title' => 'PDF File Uploads',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'PO Files',
                        'icon' => '',
                        'url' => url('/po_files'),
                    ],
                    [
                        'title' => 'PO Files(Excel)',
                        'icon' => '',
                        'url' => url('/po-files-excel'),
                    ],
                    [
                        'title' => 'Tech Pack  Files',
                        'icon' => '',
                        'url' => url('/tech-pack-files'),
                    ],
                ],
            ],

            [
                'title' => 'Fabric Booking',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Main Fabric Bookings',
                        'icon' => '',
                        'url' => url('/fabric-bookings'),
                    ],
                    [
                        'title' => 'Short Fabric Bookings',
                        'icon' => '',
                        'url' => url('/short-fabric-bookings'),
                    ],
                    [
                        'title' => 'MOQ Qty',
                        'icon' => '',
                        'url' => url('/fabric-bookings-moq-qty'),
                    ],
                    [
                        'title' => 'Service Bookings',
                        'icon' => '',
                        'url' => url('/fabric-service-bookings'),
                    ],
                    [
                        'title' => 'Sample Booking (Confirm Order)',
                        'icon' => '',
                        'url' => url('/sample-booking-for-confirm-order'),
                    ],
                    [
                        'title' => 'Sample Booking (Before Order)',
                        'icon' => '',
                        'url' => url('/sample-booking-for-before-order'),
                    ],
                ],
            ],
            [
                'title' => 'Trims Booking',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Main Trims Bookings',
                        'icon' => '',
                        'url' => url('/trims-bookings'),
                    ],
                    [
                        'title' => 'Short Trims Bookings',
                        'icon' => '',
                        'url' => url('/short-trims-bookings'),
                    ],
                    [
                        'title' => 'Sample Trims Bookings',
                        'icon' => '',
                        'url' => url('/sample-trims-booking'),
                    ],
                ],
            ],
            [
                'title' => 'Work Order',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Embellishment Work Order',
                        'icon' => '',
                        'url' => url('/work-order/embellishment'),
                    ],
                ],
            ],
            [
                'title' => 'Yarn Purchase',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Yarn Purchase Requisition',
                        'icon' => '',
                        'url' => url('/yarn-purchase/requisition'),
                    ],
                    [
                        'title' => 'Yarn Booking / Purchase Order',
                        'icon' => '',
                        'url' => url('/yarn-purchase/order'),
                    ],
                ],
            ],
            [
                'title' => 'Gate Pass',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Gate Pass Challan',
                        'icon' => '',
                        'url' => url('/gate-pass-challan'),
                    ],
                    [
                        'title' => 'Exit Point Scan',
                        'icon' => '',
                        'url' => url('/gate-pass-challan/exit-point-scan'),
                    ],
                    [
                        'title' => 'Exit List',
                        'icon' => '',
                        'url' => url('/gate-pass-challan/exit-list'),
                    ],
                ],
            ],
            [
                'title' => 'Reports',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Order Confirmation Sheet',
                        'icon' => '',
                        'url' => url('color-wise-order-volume-report')
                    ],
                    [
                        'title' => 'Color & Size Breakdown Report',
                        'icon' => '',
                        'url' => url('/order-entry-report'),
                    ],
                    [
                        'title' => 'Color & Size Merchandise wise',
                        'icon' => '',
                        'url' => url('/order-entry-report-dealing-merchant'),
                    ],
                    [
                        'title' => 'Order Details',
                        'icon' => '',
                        'url' => url('/order-details-report'),
                    ],
                    [
                        'title' => 'BOM Report',
                        'icon' => '',
                        'url' => url('/bom-report'),
                    ],
                    [
                        'title' => 'BOM Checklist',
                        'icon' => '',
                        'url' => url('/bom-report-checklist'),
                    ],
                    [
                        'title' => 'Shipment Wise Order Report',
                        'icon' => '',
                        'url' => url('/shipment-wise-order-report'),
                    ],
                    [
                        'title' => 'Buyer-PO List',
                        'icon' => '',
                        'url' => url('/buyer-season-order'),
                    ],
                    [
                        'title' => 'Buyer-PO List with Images',
                        'icon' => '',
                        'url' => url('/buyer-season-order?type=images'),
                    ],
                    [
                        'title' => 'Buyer-PO List with Color Images',
                        'icon' => '',
                        'url' => url('/buyer-season-color-order'),
                    ],
                    [
                        'title' => 'Order Volume Report',
                        'icon' => '',
                        'url' => url('/order-volume-report'),
                    ],
                    [
                        'title' => 'Budget wise WO Report',
                        'icon' => '',
                        'url' => url('/budget-wise-wo-report'),
                    ],
                    [
                        'title' => 'Style Audit Report',
                        'icon' => '',
                        'url' => url('/style-audit-report'),
                    ],
                    [
                        'title' => 'Style Audit Report Value',
                        'icon' => '',
                        'url' => url('/style-audit-report/value'),
                    ],
                    [
                        'title' => 'Current Order Status Report',
                        'icon' => '',
                        'url' => url('/current-order-status-report')
                    ],
                    [
                        'title' => 'Order Status Report',
                        'icon' => '',
                        'url' => url('/order-status-report/2')
                    ],
                    [
                        'title' => 'Order In Hand Report',
                        'icon' => '',
                        'url' => url('order-in-hand-report')
                    ],
                    [
                        'title' => 'Fabric Booking Details Report',
                        'icon' => '',
                        'url' => url('fabric-booking-summery-report')
                    ],
                    [
                        'title' => 'Final Costing Report',
                        'icon' => '',
                        'url' => url('final-costing-report')
                    ],
                    [
                        'title' => 'Price Comparison Report',
                        'icon' => '',
                        'url' => url('price-comparison-report')
                    ],
                    [
                        'title' => 'Sample Summary Report',
                        'icon' => '',
                        'url' => url('sample-summary-report')
                    ],
                ],
            ],
        ],
    ],
];
