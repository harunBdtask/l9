<?php

return [
    // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM // CRM
    [
        'title' => 'CRM',
        'priority' => 1000,
        'url' => '',
        'default' => true,
        'icon' => '&#xe1bd;',
        'items' => [
            [
                'title' => 'Marketing',
                'url' => '',
                'icon' => '',
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
                'title' => 'Sample Management',
                'url' => '',
                'icon' => '',
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
                'url' => '',
                'icon' => '',
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
                                'title' => 'Yarn Purchase Order',
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
                ],
            ],
            [
                'title' => 'Time & Action',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Task Entry',
                        'url' => url('/tna-task-entry'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'Template Creation',
                        'url' => url('/tna-template-creation'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'TNA Process Report',
                        'url' => url('/tna-reports'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'TNA Progress Report (Style Wise)',
                        'url' => url('/tna-progress-report'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'User wise task edit permission',
                        'url' => url('/user-wise-task-edit-permission'),
                        'icon' => '',
                    ],
                ],

            ]
        ],

    ],
    // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END // CRM END


    // COMMERCIAL // COMMERCIAL // COMMERCIAL // COMMERCIAL // COMMERCIAL // COMMERCIAL // COMMERCIAL
    [
        'title' => 'COMMERCIAL',
        'priority' => 2000,
        'url' => '',
        'icon' => '&#xe85c;',
        'items' => [
            [
                'title' => 'Export Zone',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Primary Contract',
                        'icon' => '',
                        'url' => url('/commercial/primary-master-contract'),
                    ],
                    [
                        'title' => 'Primary Contract Amendments',
                        'icon' => '',
                        'url' => url('/commercial/primary-master-contract-amendments'),
                    ],
                    [
                        'title' => 'Export LC Entry',
                        'icon' => '',
                        'url' => url('/commercial/export-lc'),
                    ],
                    [
                        'title' => 'Export LC Amendment',
                        'icon' => '',
                        'url' => url('/commercial/export-lc-amendments'),
                    ],
                    [
                        'title' => 'Sales Contracts',
                        'icon' => '',
                        'url' => url('/commercial/sales-contracts'),
                    ],
                    [
                        'title' => 'Sales Contract Amendment',
                        'icon' => '',
                        'url' => url('/commercial/sales-contract-amendments'),
                    ],
                    [
                        'title' => 'Export Invoice',
                        'icon' => '',
                        'url' => url('/commercial/export-invoice'),
                    ],
                    [
                        'title' => 'Document Submission',
                        'icon' => '',
                        'url' => url('/commercial/document-submission'),
                    ],
                    [
                        'title' => 'Commercial Realization',
                        'icon' => '',
                        'url' => url('/commercial/realizations'),
                    ],
                ],
            ],
            [
                'title' => 'Import Zone',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Pro Forma Invoice',
                        'icon' => '',
                        'url' => url('/commercial/proforma-invoice'),
                    ],
                    [
                        'title' => 'BTB/Margin LC',
                        'icon' => '',
                        'url' => url('/commercial/btb-margin-lc'),
                    ],
                    [
                        'title' => 'BTB/Margin LC Amendment',
                        'icon' => '',
                        'url' => url('/commercial/btb-lc-amendment'),
                    ],
                    [
                        'title' => 'Import Document Acceptance',
                        'icon' => '',
                        'url' => url('/commercial/import-document-acceptance'),
                    ],
                    [
                        'title' => 'Import Payment',
                        'icon' => '',
                        'url' => url('/commercial/import-payment'),
                    ],
                    [
                        'title' => 'Import LC Charges Entry',
                        'icon' => '',
                        'url' => url('/commercial/import-lc-charges-entry'),
                    ],
                    [
                        'title' => 'Actual Cost Entry',
                        'icon' => '',
                        'url' => url('/commercial/actual-cost-entry'),
                    ],

                ],
            ],
        ]
    ],
    // COMMERCIAL END // COMMERCIAL END // COMMERCIAL END // COMMERCIAL END // COMMERCIAL END // COMMERCIAL END


    // IE/Work Study // IE/Work Study // IE/Work Study // IE/Work Study // IE/Work Study // IE/Work Study // IE/Work Study
    [
        'title' => 'IE/WORK STUDY',
        'priority' => 3000,
        'url' => '',
        'icon' => '&#xe8f9;',
        'items' => [
            [
                'title' => 'Date Wise Cutting Targets',
                'url' => url('/date-wise-cutting-targets'),
                'icon' => '',
                'items' => [],
                'view_status' => true
            ],
            [
                'title' => 'Date Wise Cutting Targets',
                'url' => url('/v2/date-wise-cutting-targets'),
                'icon' => '',
                'items' => [],
                'view_status' => false
            ],
            [
                'title' => 'Sewing Line Target',
                'url' => url('/sewing-line-target'),
                'icon' => '',
                'items' => [],
                'view_status' => true
            ],
            [
                'title' => 'Sewing Line Target',
                'url' => url('/v2/sewing-line-target'),
                'icon' => '',
                'items' => [],
                'view_status' => false
            ],
            [
                'title' => 'SMV Justification',
                'url' => url('/show-smv'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Shipments',
                'url' => url('/shipments'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Finishing Target',
                'url' => url('/date-wise-finishing-target'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Operation Bulletins',
                'url' => url('/operation-bulletins'),
                'icon' => '',
                'items' => [],
                'view_status' => true
            ],
            [
                'title' => 'Skill Matrix',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sewing Machines',
                        'url' => url('/sewing-machines'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Sewing Processes',
                        'url' => url('/sewing-processes'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Process Assign To Machine',
                        'url' => url('/process-assign-to-machines'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Sewing Operators',
                        'url' => url('/sewing-operators'),
                        'icon' => '',
                        'items' => [],
                    ],

                ]
            ],
        ],
    ],
    // IE/Work Study END // IE/Work Study END // IE/Work Study END // IE/Work Study END // IE/Work Study END // IE/Work Study END


    // PLANNING // PLANNING // PLANNING // PLANNING // PLANNING // PLANNING // PLANNING // PLANNING // PLANNING // PLANNING
    [
        'title' => 'PLANNING',
        'priority' => 4000,
        'url' => '',
        'icon' => '&#xe430;',
        'items' => [
            [
                'title' => 'Capacity Plan',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Capacity Planning Entry',
                        'url' => url('/planning/capacity-planning-entry'),
                        'icon' => '',
                        'items' => []
                    ],
                    [
                        'title' => 'Capacity Availability',
                        'url' => url('/planning/capacity-availability'),
                        'icon' => '',
                        'items' => []
                    ],
                ]
            ],
            [
                'title' => 'Container Plan',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Container Profile',
                        'url' => url('/planning/container-profiles'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Container Summaries',
                        'url' => url('/planning/container-summaries'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Container Availability',
                        'url' => url('/planning/container-availability'),
                        'icon' => '',
                        'items' => [],
                    ],
                ]
            ],
            // [
            //     'title' => 'Cutting Plan',
            //     'priority' => 4003,
            //     'url' => '',
            //     'icon' => '&#xe14e;',
            //     'items' => [
            //         [
            //             'title' => 'Cutting Plan Permission',
            //             'url' => url('/user-cutting-floor-plan-permissions'),
            //             'icon' => '',
            //             'items' => []
            //         ],
            //         [
            //             'title' => 'Cutting Plan Board',
            //             'url' => url('/cutting-plan'),
            //             'icon' => '',
            //             'items' => []
            //         ],
            //     ]
            // ],
            [
                'title' => 'Sewing Plan',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sewing Line Capacity',
                        'url' => url('/line-capacity-entry'),
                        'icon' => '',
                        'items' => []
                    ],
                    [
                        'title' => 'Sewing Capacity Inquiry',
                        'url' => url('/order-wise-capacity-inquiry'),
                        'icon' => '',
                        'items' => []
                    ],
                    [
                        'title' => 'Sewing Plan Board',
                        'url' => url('/sewing-plan'),
                        'icon' => '',
                        'items' => []
                    ],
                ]
            ],
        ]
    ],
    // PLANNING END // PLANNING END // PLANNING END // PLANNING END // PLANNING END // PLANNING END // PLANNING END


    // INVENTORY // INVENTORY // INVENTORY // INVENTORY // INVENTORY // INVENTORY // INVENTORY // INVENTORY // INVENTORY // INVENTORY
    [
        'title' => 'INVENTORY',
        'priority' => 5000,
        'url' => '',
        'icon' => '&#xe1c2;',
        'items' => [
            [
                'title' => 'Trims Store',
                'url' => '',
                'icon' => '',
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
                ],
            ],
            [
                'title' => 'Trims Store Pro',
                'url' => '',
                'icon' => '',
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
                'url' => '',
                'icon' => '',
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
                ],
            ],
            [
                'title' => 'Grey Fabric Store',
                'url' => '',
                'icon' => '',
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
            [
                'title' => 'General Inventory',
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
                ]
            ],
            [
                'title' => 'Dyes & Chemicals Store',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Receive',
                        'icon' => '',
                        'url' => url('/dyes-store/dyes-chemical'),
                        'items' => []
                    ],
                    [
                        'title' => 'Receive Return',
                        'icon' => '',
                        'url' => url('/dyes-store/dyes-chemical-receive-return'),
                        'items' => []
                    ],
                    [
                        'title' => 'Issue',
                        'icon' => '',
                        'url' => url('/dyes-store/dyes-chemical-issue'),
                        'items' => []
                    ],
                    [
                        'title' => 'Issue Return',
                        'icon' => '',
                        'url' => url('/dyes-store/dyes-chemical-issue-return'),
                        'items' => []
                    ],
                    [
                        'title' => 'Transfer',
                        'icon' => '',
                        'url' => url('/dyes-store/dyes-chemical-transfer'),
                        'items' => []
                    ],
                ]
            ],
            [
                'title' => 'Finish Fabrics Store',
                'url' => '',
                'icon' => '',
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
                ]
            ],
            [
                'title' => 'Machine Inventory',
                'url' => '',
                'icon' => '',
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
        ]
    ],
    // INVENTORY END // INVENTORY END // INVENTORY END // INVENTORY END // INVENTORY END // INVENTORY END // INVENTORY END


    // TEX PRO // TEX PRO // TEX PRO // TEX PRO // TEX PRO // TEX PRO // TEX PRO // TEX PRO // TEX PRO // TEX PRO // TEX PRO
    [
        'title' => 'TEXTILE PRO',
        'priority' => 6000,
        'url' => '',
        'icon' => '&#xe8e1;',
        'items' => [
            [
                'title' => 'Spinning',
                'url' => '',
                'icon' => '',
                'items' => []
            ],
            [
                'title' => 'Knitting',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Fabric Booking List',
                        'url' => url('/knitting/fabric-booking-list'),
                        'icon' => '',
                        'items' => []
                    ],
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
                ]
            ],
            [
                'title' => 'Textile Dyeing(In-House)',
                'url' => '',
                'icon' => '',
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
                                'url' => url('/dyeing/textile-orders/create'),
                            ],
                            [
                                'title' => 'Order List',
                                'icon' => '',
                                'url' => url('/dyeing/textile-orders'),
                            ],
                        ]
                    ],
                    [
                        'title' => 'Dyeing Process',
                        'priority' => '',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Batches',
                                'icon' => '',
                                'url' => url('/dyeing/dyeing-batches'),
                            ],
                            [
                                'title' => 'Recipes',
                                'icon' => '',
                                'url' => url('/dyeing/recipes'),
                            ],
                            [
                                'title' => 'Recipe Requisitions',
                                'icon' => '',
                                'url' => url('/dyeing/recipes/requisitions'),
                            ],
                            [
                                'title' => 'Dyeing Production',
                                'icon' => '',
                                'url' => url('/dyeing/productions'),
                            ],
                            [
                                'title' => 'Brush',
                                'icon' => '',
                                'url' => url('/dyeing/finishing-productions'),
                            ],
                            [
                                'title' => 'Dryer',
                                'icon' => '',
                                'url' => url('/dyeing/dryer'),
                            ],
                            [
                                'title' => 'Slitting',
                                'icon' => '',
                                'url' => url('/dyeing/slittings'),
                            ],
                            [
                                'title' => 'Stentering',
                                'icon' => '',
                                'url' => url('/dyeing/stenterings'),
                            ],
                            [
                                'title' => 'Compactor',
                                'icon' => '',
                                'url' => url('/dyeing/compactors'),
                            ],
                            [
                                'title' => 'Tumble',
                                'icon' => '',
                                'url' => url('/dyeing/tumbles'),
                            ],
                            [
                                'title' => 'Peach',
                                'icon' => '',
                                'url' => url('/dyeing/peaches'),
                            ],
                            [
                                'title' => 'Dyeing Goods Delivery',
                                'icon' => '',
                                'url' => url('/dyeing/dyeing-goods-delivery'),
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Yarn Dyeing',
                'url' => '',
                'icon' => '',
                'items' => []
            ]

        ],
    ],
    // TEX PRO END // TEX PRO END // TEX PRO END // TEX PRO END // TEX PRO END // TEX PRO END // TEX PRO END // TEX PRO END // TEX PRO END


    // GMT PRO // GMT PRO // GMT PRO // GMT PRO // GMT PRO // GMT PRO // GMT PRO // GMT PRO // GMT PRO // GMT PRO // GMT PRO
    [
        'title' => 'GARMENTS PRO',
        'priority' => 7000,
        'url' => '',
        'icon' => '&#xe3b9;',
        'items' => [
            [
                'title' => 'Protracker',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Cutting Droplets',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Bundle Card[Knit]',
                                'url' => url('/bundle-card-generations'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Bundle Card[Manual]',
                                'url' => url('/bundle-card-generation-manual'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Cutting Scan',
                                'url' => url('/cutting-scan'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Challan Wise Bundle',
                                'url' => url('/challan-wise-bundle'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Bundle Card Replace',
                                'url' => url('/replace-bundle-card'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Update Cutting Production',
                                'url' => url('/update-cutting-production'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Cutting Qty Request',
                                'url' => url('/cutting-qty-request'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Print/Embr. Droplets',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Send To Print/Embr.',
                                'url' => url('/print-send-scan'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Receive From Print/Embr.',
                                'url' => url('/bundle-received-from-print'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Gatepass List',
                                'url' => url('/gatepasses'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Gatepass List (Archived)',
                                'url' => url('/archived-gatepasses'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Input Droplets',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Solid Input/Tag',
                                'url' => url('/cutting-inventory-scan'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Challan List',
                                'url' => url('/view-challan-list'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Tag List',
                                'url' => url('/view-tag-list'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Gatepass List',
                                'url' => url('/gatepasses'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Challan Wise Bundles',
                                'url' => url('/challan-wise-bundles'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Challan List (Archived)',
                                'url' => url('/view-archived-challan-list'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Sewing Droplets',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Sewing Output Scan',
                                'url' => url('/sewing-output-scan'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Sewing Challan List',
                                'url' => url('/sewingoutput-challan-list'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Washing Droplets',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Send To Wash',
                                'url' => url('/washing-scan'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Received Form Wash',
                                'url' => url('/received-bundle-from-wash'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Manual Washing Received Challan List',
                                'url' => url('/manual-washing-received-challan-list'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Washing Challan List',
                                'url' => url('/washing-challan-list'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Finishing Droplets',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'ERP Packing List',
                                'url' => url('/erp-packing-list'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'ERP Packing List V2',
                                'url' => url('/erp-packing-list-v2'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'ERP Packing List V3',
                                'url' => url('/erp-packing-list-v3'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Packing List',
                                'url' => url('/packing-list-generate'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Iron Poly & Packings',
                                'url' => url('/iron-poly-packings'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Hourly Finishing Production',
                                'url' => url('/hour-wise-finishing-production'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Production',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Cutting Production Entry',
                        'url' => url('/manual-cutting-production'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Cutting Delivery To Input Challan',
                        'url' => url('/manual-cutting-delivery-input'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Embellishment Issue Entry',
                        'url' => url('/manual-embellishment-issue'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Embellishment Receive Entry',
                        'url' => url('/manual-embellishment-receive'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Sewing Input Entry',
                        'url' => url('/manual-sewing-input'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Sewing Output Entry',
                        'url' => url('/manual-sewing-output'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Iron Entry',
                        'url' => url('/manual-finishing-iron-production'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Poly Packaging Entry',
                        'url' => url('/manual-finishing-poly-packing-production'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Inspection Entry',
                        'url' => url('/manual-inspection'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Shipment Entry',
                        'url' => url('/manual-shipment'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Subcontract',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Factory Profile',
                                'url' => url('/subcontract-factory-profile'),
                                'icon' => '',
                            ],
                            [
                                'title' => 'Cutting Floor',
                                'url' => url('/subcontract-cutting-floor'),
                                'icon' => '',
                            ],
                            [
                                'title' => 'Cutting Table',
                                'url' => url('/subcontract-cutting-table'),
                                'icon' => '',
                            ],
                            [
                                'title' => 'Sewing Floor',
                                'url' => url('/subcontract-sewing-floor'),
                                'icon' => '',
                            ],
                            [
                                'title' => 'Sewing Line',
                                'url' => url('/subcontract-sewing-line'),
                                'icon' => '',
                            ],
                            [
                                'title' => 'Embellishment Floor',
                                'url' => url('/subcontract-embellishment-floor'),
                                'icon' => '',
                            ],
                            [
                                'title' => 'Finishing Floor',
                                'url' => url('/subcontract-finishing-floor'),
                                'icon' => '',
                            ],
                            [
                                'title' => 'Finishing Table',
                                'url' => url('/subcontract-finishing-table'),
                                'icon' => '',
                            ],
                        ]
                    ],
                ]
            ]
        ],
    ],
    // GMT PRO END // GMT PRO END // GMT PRO END // GMT PRO END // GMT PRO END // GMT PRO END // GMT PRO END // GMT PRO END


    // SUBCONTRACT // SUBCONTRACT // SUBCONTRACT // SUBCONTRACT // SUBCONTRACT // SUBCONTRACT // SUBCONTRACT // SUBCONTRACT
    [
        'title' => 'SUBCONTRACT',
        'priority' => 8600,
        'url' => '',
        'icon' => '&#xe85d;',
        'items' => [
            [
                'title' => 'Textile Subcontract',
                'url' => '',
                'icon' => '',
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
                ],
            ],
            [
                'title' => 'Garments',
                'url' => '',
                'icon' => '',
                'items' => []
            ]
        ],
    ],
    // SUBCONTRACT END // SUBCONTRACT END // SUBCONTRACT END // SUBCONTRACT END // SUBCONTRACT END // SUBCONTRACT END


    // PROCUREMENT // PROCUREMENT // PROCUREMENT // PROCUREMENT // PROCUREMENT // PROCUREMENT // PROCUREMENT // PROCUREMENT
    [
        'title' => 'PROCUREMENT',
        'priority' => 9600,
        'url' => '',
        'icon' => '&#xe431;',
        'items' => [
            [
                'title' => 'Requisitions',
                'url' => url('procurement/requisitions'),
                'icon' => '',
            ],
            [
                'title' => 'Quotations',
                'url' => url('procurement/quotations'),
                'icon' => '',
            ],
            [
                'title' => 'Purchase Orders',
                'url' => url('procurement/purchase-order'),
                'icon' => '',
            ],
            // [
            //     'title' => 'GRN',
            //     'url' => url('basic-finance/vouchers'),
            //     'icon' => '',
            // ],
        ],
    ],
    // PROCUREMENT END // PROCUREMENT END // PROCUREMENT END // PROCUREMENT END // PROCUREMENT END // PROCUREMENT END


    // FIN & ACCOUNTING // FIN & ACCOUNTING // FIN & ACCOUNTING // FIN & ACCOUNTING // FIN & ACCOUNTING // FIN & ACCOUNTING
    [
        'title' => 'ACCOUNTING',
        'priority' => 10000,
        'url' => '',
        'icon' => '&#xe84f;',
        'items' => [
            [
               'title' => 'New Accounting & Finance',
               'url' => '',
               'icon' => '',
               'items' => [
                    [
                        'title' => 'Chart of Accounts',
                        'url' => url('finance/accounts'),
                        'icon' => '',
                    ],
                    [
                        'title' => 'Library',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Projects',
                                        'icon' => '',
                                        'url' => url('finance/projects'),
                                    ],
                                    [
                                        'title' => 'Unit',
                                        'icon' => '',
                                        'url' => url('finance/units'),
                                    ],
                                    [
                                        'title' => 'Departments',
                                        'icon' => '',
                                        'url' => url('finance/departments'),
                                    ],
                                    [
                                        'title' => 'Cost Centers',
                                        'icon' => '',
                                        'url' => url('finance/cost-centers'),
                                    ],
                                    [
                                        'title' => 'Suppliers',
                                        'icon' => '',
                                        'url' => url('finance/suppliers'),
                                    ],
                                    [
                                        'title' => 'Bank Name Create',
                                        'icon' => '',
                                        'url' => '',
                                        'items' => [
                                            [
                                                'title' => 'Banks',
                                                'icon' => '',
                                                'url' => url('/finance/banks'),
                                            ],
                                            [
                                                'title' => 'Bank Accounts',
                                                'icon' => '',
                                                'url' => url('/finance/bank-accounts'),
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                       'title' => 'Voucher',
                       'url' => '',
                       'icon' => '',
                       'items' => [
                           [
                               'title' => 'Entry',
                               'icon' => '',
                               'url' => '',
                               'items' => [
                                   [
                                       'title' => 'Voucher Entry',
                                       'icon' => '',
                                       'url' => url('finance/vouchers/entry'),
                                   ],
                                   [
                                       'title' => 'Office Note',
                                       'icon' => '',
                                       'url' => url('finance/maintenance'),
                                   ],
                               ],
                           ],
                           [
                               'title' => 'Reports',
                               'icon' => '',
                               'url' => '',
                               'items' => [
                                   [
                                       'title' => 'Vouchers List',
                                       'icon' => '',
                                       'url' => url('finance/vouchers'),
                                   ],
                                   [
                                       'title' => 'Vouchers Approval Panel',
                                       'icon' => '',
                                       'url' => url('finance/vouchers-approve-panels'),
                                   ]
                               ],
                           ],
                       ],
                    ],
                    [
                       'title' => 'Bank Management',
                       'url' => '',
                       'icon' => '',
                       'items' => [
                           [
                               'title' => 'Cheque Register',
                               'icon' => '',
                               'url' => url('finance/cheque-books'),
                           ],
                           [
                               'title' => 'Cheque Clear',
                               'icon' => '',
                               'url' => url('finance/cheque-clear'),
                           ],
                           [
                               'title' => 'Cheque Clear List',
                               'icon' => '',
                               'url' => url('finance/clear-cheque-list'),
                           ],
                       ],
                    ],
                    [
                        'title' => 'Supplier Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Bill Entry',
                                'icon' => '',
                                'url' => url('finance/supplier-bill-entry'),
                            ],
                            [
                                'title' => 'Bill Payment',
                                'icon' => '',
                                'url' => url('finance/supplier-bill-payment'),
                            ]
                        ],
                    ],
                    [
                        'title' => 'Customer Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Create Invoice',
                                'icon' => '',
                                'url' => url('finance/customer-bill-entry'),
                            ],
                            [
                                'title' => 'Bill Receive',
                                'icon' => '',
                                'url' => url('finance/customer-bill-payment'),
                            ]
                        ],
                    ],
                ]
            ],
            [
                'title' => 'Accounting & Finance',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Library',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                [
                    'title' => 'Projects',
                    'icon' => '',
                    'url' => url('basic-finance/projects'),
                ],
                [
                    'title' => 'Units',
                    'icon' => '',
                    'url' => url('basic-finance/units'),
                ],
                [
                    'title' => 'Departments',
                    'icon' => '',
                    'url' => url('basic-finance/departments'),
                ],
                [
                    'title' => 'Cost Centers',
                    'icon' => '',
                    'url' => url('basic-finance/cost-centers'),
                ],
                [
                    'title' => 'Chart of Accounts',
                    'icon' => '',
                    'url' => url('basic-finance/accounts'),
                ],
                [
                    'title' => 'Supplier Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Customer Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Employee Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Others Name Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Loan Account Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Investment Account Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Fixed Asset Item Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Payment Mode create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Currency Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                [
                    'title' => 'Bank Name Create',
                    'icon' => '',
                    'url' => '',
                    'items' => [
                        [
                            'title' => 'Banks',
                            'icon' => '',
                            'url' => url('/basic-finance/banks'),
                        ],
                        [
                            'title' => 'Bank Accounts',
                            'icon' => '',
                            'url' => url('/basic-finance/bank-accounts'),
                        ],
                        [
                            'title' => 'Cheque Books',
                            'icon' => '',
                            'url' => url('/basic-finance/cheque-books'),
                        ],
                        [
                            'title' => 'Receive Bank List',
                            'icon' => '',
                            'url' => url('/basic-finance/receive-banks'),
                        ],
                        [
                            'title' => 'Receive Cheque List',
                            'icon' => '',
                            'url' => url('/basic-finance/receive-cheques'),
                        ],
                    ],
                ],
                [
                    'title' => 'LC Create',
                    'icon' => '',
                    'url' => url('basic-finance/maintenance'),
                ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Voucher',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Debit Voucher',
                                        'icon' => '',
                                        'url' => url('basic-finance/vouchers/create?voucher_type=debit'),
                                    ],
                                    [
                                        'title' => 'Credit Voucher',
                                        'icon' => '',
                                        'url' => url('basic-finance/vouchers/create?voucher_type=credit'),
                                    ],
                                    [
                                        'title' => 'Journal Voucher',
                                        'icon' => '',
                                        'url' => url('basic-finance/vouchers/create?voucher_type=journal'),
                                    ],
                                    [
                                        'title' => 'Contra Voucher',
                                        'icon' => '',
                                        'url' => url('basic-finance/vouchers/create?voucher_type=contra'),
                                    ],
                                    [
                                        'title' => 'Office Note',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Cash Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Cash Budget',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Cash Requisition',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'IOU Issue',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'IOU Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Cash Transfer',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Bank Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'View',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Bank Center',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Cheque book entry',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Cheque Issue',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Cheque Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/cheque-books'),
                                    ],
                                    [
                                        'title' => 'Cheque Clear',
                                        'icon' => '',
                                        'url' => url('basic-finance/cheque-clear'),
                                    ],
                                    [
                                        'title' => 'Payment Realization Entry',
                                        'icon' => '',
                                        'url' => url('basic-finance/accounting-realization'),
                                    ],
                                    [
                                        'title' => 'Bill Purchase Entry',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'LC Payment',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'FDR Creation',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'FDR Interest Applied',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Bank to Bank Transfer',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Limit Setup',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Loan Account',
                                        'icon' => '',
                                        'url' => url('basic-finance/loan/accounts'),
                                    ],
                                    [
                                        'title' => 'Loan Disbursement',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Loan Amortization Schedule',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Interest Applied',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Bank Reconciliation',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Vendor Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'View',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Vendor Center',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Enter Bills',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Pay Bills',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Debit Note',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Purchase Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Purchase Center',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Comparative Statement',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Work Order',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Proforma Invoice',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Bill Entry',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Purchase Return',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Customers Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Customer Center',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Payment Receive',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Credit Note',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Sales Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'View',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Order List',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Pre-Cost List',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Sales Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Sales Return Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Sales Center',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Invoice',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Sales Return',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Assets Manager',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'View',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Assets Center',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Depreciation Process',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Assets Held for sale',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Asset Disposal',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Assets Book for Maintenance',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Assets Schedule Maintenance',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Inventories Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'View',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Inventory Center',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Item Assembly',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Inventory Adjustment',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Landed Cost',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Cost & Budget Management',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Entry',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Master Budget',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Revenue & Expenditure Budget',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Order Pre-Costing',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Contribution Margin',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Dyes Chemical Costing',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Order Wise Process Costing',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Requisition & Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Ac Requisition',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Fund Requisition',
                                        'url' => url('basic-finance/fund-requisition'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Audit Approval',
                                        'url' => url('basic-finance/fund-requisition/audit-approval'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Account Approval',
                                        'url' => url('basic-finance/fund-requisition/account-approval'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Requisition Report',
                                        'url' => url('basic-finance/fund-requisition/reports'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Purpose',
                                        'url' => url('basic-finance/fund-requisition/purposes'),
                                        'icon' => '',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ],
        ]
    ],
    // FIN & ACCOUNTING END // FIN & ACCOUNTING END // FIN & ACCOUNTING END // FIN & ACCOUNTING END // FIN & ACCOUNTING END


    // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM // HRM
    [
        'title' => 'HRM',
        'priority' => 11000,
        'url' => '',
        'icon' => '&#xe7fb;',
        'items' => [
            [
                'title' => 'Employee',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Employee Entry',
                        'icon' => '',
                        'url' => url('/hr/employee/create'),
                    ],
                    [
                        'title' => 'Worker List',
                        'icon' => '',
                        'url' => url('CRM'),
                    ],
                    [
                        'title' => 'Staff List',
                        'icon' => '',
                        'url' => url('/hr/employee-staff-list'),
                    ],
                    [
                        'title' => 'Management List',
                        'icon' => '',
                        'url' => url('/hr/employee-management-list'),
                    ],
                    [
                        'title' => 'Check List',
                        'icon' => '',
                        'url' => url('/hr/employee/checklist'),
                    ],
                    [
                        'title' => 'Salary History',
                        'icon' => '',
                        'url' => url('/hr/employee/salary-history'),
                    ],
                    [
                        'title' => 'Identity Card',
                        'icon' => '',
                        'url' => url('/hr/employee/identity-card'),
                    ],
                    [
                        'title' => 'Appointment Letter',
                        'icon' => '',
                        'url' => url('/hr/employee/appointment-letter'),
                    ],
                    [
                        'title' => 'Disciplinary Information',
                        'icon' => '',
                        'url' => url('/hr/employee/disciplinary-information'),
                    ],
                ]
            ],
            [
                'title' => 'Attendance',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Attendance Dashboard',
                        'icon' => '',
                        'url' => url('/hr/attendance/dashboard'),
                    ],
                    [
                        'title' => 'Attendance List',
                        'icon' => '',
                        'url' => url('/hr/attendance'),
                    ],
                    [
                        'title' => 'Manual Attendance List',
                        'icon' => '',
                        'url' => url('/hr/attendance/manual-entry'),
                    ],
                    [
                        'title' => 'Manual Attendance Create',
                        'icon' => '',
                        'url' => url('/hr/attendance/manual-entry/create'),
                    ],
                    [
                        'title' => 'Attendance Checklist',
                        'icon' => '',
                        'url' => url('/hr/attendance/check-list'),
                    ],
                    [
                        'title' => 'Absent Employee List',
                        'icon' => '',
                        'url' => url('/hr/attendance/absent-list'),
                    ],
                    [
                        'title' => 'OT Approval List',
                        'icon' => '',
                        'url' => url('/hr/attendance/ot-approval-list'),
                    ],
                    [
                        'title' => 'Night OT List',
                        'icon' => '',
                        'url' => url('/hr/attendance/night-ot-list'),
                    ],
                    [
                        'title' => 'Attendance Profile',
                        'icon' => '',
                        'url' => url('/hr/attendance/profile'),
                    ],
                    [
                        'title' => 'Continuous Absent Report',
                        'icon' => '',
                        'url' => url('/hr/attendance/continuous-absent/report'),
                    ],
                    [
                        'title' => 'Employee Job Card',
                        'icon' => '',
                        'url' => url('/hr/attendance/employee-job-card/regular'),
                    ],
                    [
                        'title' => 'Employee Job Card (FD)',
                        'icon' => '',
                        'url' => url('/hr/attendance/employee-job-card'),
                    ],
                    [
                        'title' => 'Daily Roasting',
                        'icon' => '',
                        'url' => url('/hr/attendance/daily-roasting'),
                    ],
                ]
            ],
            [
                'title' => 'Leave',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Application',
                        'icon' => '',
                        'url' => url('/hr/leave/application'),
                    ],
                    [
                        'title' => 'Application List',
                        'icon' => '',
                        'url' => url('/hr/leave/application-list'),
                    ],
                ]
            ],
            [
                'title' => 'Payroll',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Process Payment',
                        'icon' => '',
                        'url' => url('/hr/payroll/process-payment'),
                    ],
                    [
                        'title' => 'Print PaySlip',
                        'icon' => '',
                        'url' => url('/hr/payroll/print-payslip'),
                    ],
                ]
            ],
        ]
    ],
    // HRM END // HRM END // HRM END // HRM END // HRM END // HRM END // HRM END // HRM END // HRM END // HRM END


    // APPROVAL // APPROVAL // APPROVAL // APPROVAL // APPROVAL // APPROVAL // APPROVAL // APPROVAL // APPROVAL
    [
        'title' => 'APPROVAL',
        'priority' => 12000,
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
                'title' => 'Yarn Store Approval',
                'url' => url('/approvals/modules/yarn-store'),
                'icon' => '',
            ],
            [
                'title' => 'Dyes Chemical Store Approval',
                'url' => url('/approvals/modules/dyes-chemical-store'),
                'icon' => '',
            ],
            // [
            //     'title' => 'Inventory',
            //     'url' => '',
            //     'icon' => '',
            //     'items' => [
            //         [
            //             'title' => 'Yarn Store Approval',
            //             'url' => url('/approvals/modules/yarn-store'),
            //             'icon' => '',
            //         ],
            //         [
            //             'title' => 'Dyes Chemical Store Approval',
            //             'url' => url('/approvals/modules/dyes-chemical-store'),
            //             'icon' => '',
            //         ],
            //     ]
            // ],
        ],
    ],
    // APPROVAL END // APPROVAL END // APPROVAL END // APPROVAL END // APPROVAL END // APPROVAL END // APPROVAL END


    // AI/BI/4IR // AI/BI/4IR // AI/BI/4IR // AI/BI/4IR // AI/BI/4IR // AI/BI/4IR // AI/BI/4IR // AI/BI/4IR // AI/BI/4IR
    [
        'title' => 'AI/BI/4IR',
        'priority' => 13000,
        'url' => '',
        'icon' => '&#xe562;',
        'items' => [
            [
                'title' => 'Audit Report',
                'url' => url('/audit-report'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Color Wise Production Summary Report',
                'url' => url('/color-wise-production-summary-report'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Monthly Efficiency Summary Report',
                'url' => url('/monthly-efficiency-summary-report'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Factory Wise Cutting Report',
                'url' => url('/factory-wise-cutting-report'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Factory Wise Print Send & Receive Report',
                'url' => url('/factory-wise-print-sent-received-report'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Factory Wise Input & Output Report',
                'url' => url('/factory-wise-input-output-report'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Cut To Finish Report',
                'url' => url('/cut-to-finish-report'),
                'icon' => '',
                'items' => [],
            ],
        ],
    ],
    // AI/BI/4IR END // AI/BI/4IR END // AI/BI/4IR END // AI/BI/4IR END // AI/BI/4IR END // AI/BI/4IR END // AI/BI/4IR END


    // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS // MIS
    [
        'title' => 'MIS',
        'priority' => 14000,
        'url' => '',
        'icon' => '&#xe85d;',
        'items' => [
            [
                'title' => 'CRM',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Merchandising Reports',
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
                        ]
                    ]
                ],
            ],
            [
                'title' => 'COMMERCIAL',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Commercial Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Primary BTB Status',
                                'icon' => '',
                                'url' => url('/commercial/pmc-btb-status'),
                            ],
                            [
                                'title' => 'Contract/LC Status',
                                'icon' => '',
                                'url' => url('/commercial/contract-lc-status'),
                            ],
                            [
                                'title' => 'BTB Status',
                                'icon' => '',
                                'url' => url('/commercial/btb-status'),
                            ],
                            [
                                'title' => 'Export LC Status',
                                'icon' => '',
                                'url' => url('/commercial/export-lc-status'),
                            ],
                            [
                                'title' => 'BTB Liability Coverage Report',
                                'icon' => '',
                                'url' => url('/commercial/btb-liability-coverage'),
                            ],
                            [
                                'title' => 'Export CI Statement',
                                'icon' => '',
                                'url' => url('/commercial/export-ci-statement'),
                            ],
                            [
                                'title' => 'Export Import Status Report',
                                'icon' => '',
                                'url' => url('/commercial/export-import-status'),
                            ],
                            [
                                'title' => 'Export LC Sales Contract Report',
                                'icon' => '',
                                'url' => url('/commercial/export-lc-sales'),
                            ],
                            [
                                'title' => 'Export Statement as of today',
                                'icon' => '',
                                'url' => url('/commercial/export-statement-today'),
                            ],
                            [
                                'title' => 'File Wise Export Import Status',
                                'icon' => '',
                                'url' => url('/commercial/file-wise-export-import'),
                            ],
                            [
                                'title' => 'File Wise export Status',
                                'icon' => '',
                                'url' => url('/commercial/file-wise-export-status'),
                            ],
                            [
                                'title' => 'Monthly Bank Submission',
                                'icon' => '',
                                'url' => url('/commercial/monthly-bank-submission'),
                            ],
                            [
                                'title' => 'Monthly Export Import',
                                'icon' => '',
                                'url' => url('/commercial/monthly-export-import'),
                            ],
                            [
                                'title' => 'Order Wise Export Invoice Report',
                                'icon' => '',
                                'url' => url('/commercial/order-wise-export-invoice'),
                            ],
                            [
                                'title' => 'Yarn Work Order Statement',
                                'icon' => '',
                                'url' => url('/commercial/yarn-work-order-statement'),
                            ],
                            [
                                'title' => 'Performance Report',
                                'icon' => '',
                                'url' => url('/commercial/performance-report'),
                            ],
                            [
                                'title' => 'PI Tracking Report',
                                'icon' => '',
                                'url' => url('/commercial/pi-tracking-report'),
                            ],
                        ],
                    ],
                ]
            ],
            [
                'title' => 'IE/WORD STUDY',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'IE Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Style, PO & Color Wise Input',
                                'url' => url('/booking-no-po-and-color-report'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'All orders Shipment Summary',
                                'url' => url('/all-orders-shipment-summary'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Buyer Wise Shipment Report',
                                'url' => url('/buyer-wise-shipment-report'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Daily Shipment Report',
                                'url' => url('/daily-shipment-report'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Overall Shipment Report',
                                'url' => url('/overall-shipment-report'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Weekly Shipment Schedule Report',
                                'url' => url('/weekly-shipment-schedule'),
                                'icon' => '',
                                'items' => []
                            ],
                        ],
                    ],
                    [
                        'title' => 'Skill Matrix Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Operator Skill Inventory',
                                'url' => url('/operator-skill-inventory'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'PLANNING',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sewing Plan Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Sewing Plan Report',
                                'url' => url('/sewing-line-plan-report'),
                                'icon' => '',
                                'items' => [],
                            ]
                        ]
                    ],
                    [
                        'title' => 'Capacity Plan Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Capacity vs Marketing',
                                'url' => url('/planning/reports/capacity-marketing-comparisons'),
                                'icon' => '',
                            ],
                        ],
                    ],
                ]
            ],
            [
                'title' => 'INVENTORY',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Trims Store Reports',
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
                    [
                        'title' => 'Yarn Store Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
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
                    [
                        'title' => 'General Inventory Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
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
                    [
                        'title' => 'Dyes & Chemicals Store Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Stock Summary Report',
                                'icon' => '',
                                'url' => url('/dyes-store/dyes-stock-summary-report/daily'),
                                'items' => []
                            ],
                            [
                                'title' => 'Sub Store One Stock Summary R.',
                                'icon' => '',
                                'url' => url('/dyes-store/dyes-stock-summary-report?store_id=5'),
                                'items' => []
                            ],
                            [
                                'title' => 'Sub Store Two Stock Summary R.',
                                'icon' => '',
                                'url' => url('/dyes-store/dyes-stock-summary-report?store_id=6'),
                                'items' => []
                            ],
                            [
                                'title' => 'Stock Issue Report',
                                'icon' => '',
                                'url' => url('/dyes-store/dyes-stock-summary-report-two?store_id='),
                                'items' => []
                            ],
                            [
                                'title' => 'Dyes & Chemical Receive Report',
                                'icon' => '',
                                'url' => url('/dyes-store/dyes-and-chemical-receive-report'),
                                'items' => []
                            ],
                        ]
                    ],
                    [
                        'title' => 'Finish Fabrics Store Reports',
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
                ]
            ],
            [
                'title' => 'TEXTILE PRO',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Knitting Reports',
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
                    [
                        'title' => 'Textile Dyeing(In-House) Reports',
                        'priority' => '',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Daily Dyeing Production Report',
                                'icon' => '',
                                'url' => url('/dyeing/daily-dyeing-production-report'),
                            ],
                            [
                                'title' => 'Party And Order Wise Report',
                                'icon' => '',
                                'url' => url('/dyeing/party-and-order-wise-report'),
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'GARMENTS PRO',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Protracker',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Cutting Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Cutting Dashboard',
                                        'url' => url('/cutting-dashboard'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'All PO\'s Report',
                                        'url' => url('/all-orders-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Buyer Wise Report',
                                        'url' => url('/buyer-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Style, PO & Color Wise Report',
                                        'url' => url('/booking-no-po-and-color-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Excess Cutting Report',
                                        'url' => url('/excess-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Cutting Report',
                                        'url' => url('/daily-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Report',
                                        'url' => url('/date-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Report V2',
                                        'url' => url('/v2/date-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Month Wise Report',
                                        'url' => url('/month-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Monthly Table Wise Cutting Report',
                                        'url' => url('/monthly-table-wise-cutting-production-summary-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Cutting Wise Report',
                                        'url' => url('/cutting-no-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Lot Wise Report',
                                        'url' => url('/lot-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Consumption Report',
                                        'url' => url('/consumption-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Buyer Style Wise Fabric Consumption',
                                        'url' => url('/buyer-style-wise-fabric-consumption-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Buyer Style Wise Cutting Report',
                                        'url' => url('/buyer-style-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Fabric Consumption',
                                        'url' => url('/daily-fabric-consumption-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Monthly Fabric Consumption',
                                        'url' => url('/monthly-fabric-consumption-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Bundle Scan Check',
                                        'url' => url('/bundle-scan-check'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Table Wise Cutting & Input Summary',
                                        'url' => url('/cutting-production-summary-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Size Wise Cutting Report',
                                        'url' => url('/daily-size-wise-cutting-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Daily Cutting Balance Report',
                                        'url' => url('/daily-cutting-balance-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Color Size Summary Report',
                                        'url' => url('/color-size-summary-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Cutting Production Report V2',
                                        'url' => url('/cutting-production-report-v2'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Monthly Cutting Input Report',
                                        'url' => url('/monthly-cutting-input-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Yearly Summary Report',
                                        'url' => url('/yearly-summary-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Order Wise Cutting Report',
                                        'url' => url('/v2/all-orders-cutting-report'),
                                        'icon' => '',
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Print/Embr. Droplets Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Buyer Wise Report',
                                        'url' => url('/buyer-wise-print-send-receive-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Style, PO & Color Wise Report',
                                        'url' => url('/booking-no-po-and-color-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Cutting Wise Report',
                                        'url' => url('/cutting-no-wise-color-print-send-receive-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Report',
                                        'url' => url('/date-wise-print-send-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Print Emb. Report',
                                        'url' => url('/daily-print-embr-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Bundle Scan Check',
                                        'url' => url('/bundle-scan-check'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Input Droplets Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Input Dashboard',
                                        'url' => url('/sewing-input-dashboard'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Input Dashboard V2',
                                        'url' => url('/input-dashboard-v2'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'All PO\'s Inventory Summary',
                                        'url' => url('/order-wise-cutting-inventory-summary'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Cutting Wise Inventory Challan',
                                        'url' => url('/cutting-no-wise-inventory-challan'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Cutting Wise Report',
                                        'url' => url('/cutting-no-wise-cutting-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Challan Count',
                                        'url' => url('/inventory-challan-count'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'All PO\'s Input Summary',
                                        'url' => url('/order-sewing-line-input'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Buyer Wise Input',
                                        'url' => url('/buyer-sewing-line-input'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Style, PO & Color Wise Report',
                                        'url' => url('/booking-no-po-and-color-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Input',
                                        'url' => url('/date-wise-sewing-input'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Month Wise Input',
                                        'url' => url('/date-range-or-month-wise-sewing-input'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Floor & Line Wise Report',
                                        'url' => url('/floor-line-wise-sewing-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Input Closing',
                                        'url' => url('/input-closing'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Bundlecard Scan Check',
                                        'url' => url('/bundle-scan-check'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Line Wise Input Inhand Report',
                                        'url' => url('floor-line-wise-input-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Input Status',
                                        'url' => url('/daily-input-status'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Input Status V2',
                                        'url' => url('/v2/daily-input-status'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Order Wise Input Report',
                                        'url' => url('/order-wise-sewing-input-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Size Wise Input Report',
                                        'url' => url('/report/daily-size-wise-input'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Line Size Wise Input Report',
                                        'url' => url('/line-size-wise-input-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Sewing Droplets Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'All PO\'s Summary',
                                        'url' => url('/all-orders-sewing-output-summary'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Buyer Wise Output',
                                        'url' => url('/buyer-wise-sewing-output'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Style, PO & Color Wise Report',
                                        'url' => url('/booking-no-po-and-color-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Floor & Line Wise Report',
                                        'url' => url('/floor-line-wise-sewing-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Line Wise Hr Prod.',
                                        'url' => url('/line-wise-hourly-sewing-output'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Hr Prod.',
                                        'url' => url('/date-wise-hourly-sewing-output'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Output',
                                        'url' => url('/date-wise-sewing-output'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Daily Input Output Summary',
                                        'url' => url('/daily-input-output-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Monthly Production Summary',
                                        'url' => url('/monthly-line-wise-production-summary-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Line & Date Wise Avg.',
                                        'url' => url('/line-date-wise-output-avg'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Production on Graph',
                                        'url' => url('/production-dashboard'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Production on Graph V2',
                                        'url' => url('/production-dashboard-v2'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Production on Graph V3',
                                        'url' => url('/production-dashboard-v3'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Production on Graph V4',
                                        'url' => url('/production-dashboard-v4'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Production on Graph V5',
                                        'url' => url('/production-dashboard-v5'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Bundle Wise QC',
                                        'url' => url('/bundle-wise-qc'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Get Challans By Bundlecard',
                                        'url' => url('/get-challans-by-bundlecard'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Individual Bundle Scan Check',
                                        'url' => url('/individual-bundle-scan-check'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Style Balance Bundle Check',
                                        'url' => url('/booking-balance-bundle-scan-check'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Sewing Forecast Report',
                                        'url' => url('/sewing-forcast-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Washing Droplets Report',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'All Order\'s Summary',
                                        'url' => url('/order-wise-receievd-from-wash'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Buyer Wise Report',
                                        'url' => url('/buyer-wise-receievd-from-wash'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Washing Report',
                                        'url' => url('/date-wise-washing-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Finishing Droplets Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Order Wise Finishing V1',
                                        'url' => url('/finishing-receieved-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Order Wise Finishing V2',
                                        'url' => url('/order-wise-finishing-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Color Wise Finishing',
                                        'url' => url('/color-wise-finishing-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Finishing Summary Report',
                                        'url' => url('/finishing-summary-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Style Wise Finishing Summary Report',
                                        'url' => url('/style-wise-finishing-summary-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date Wise Finishing',
                                        'url' => url('/date-wise-finishing-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'All Order\'s Poly & Cartoon',
                                        'url' => url('/all-orders-poly-cartoon-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Date wise Iron Poly & Packing',
                                        'url' => url('/date-wise-iron-poly-packing-summary'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Finishing Production Status',
                                        'url' => url('/finishing-production-status'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'PO & Shipment Status',
                                        'url' => url('/po-shipment-status'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Hourly Finishing Production Report',
                                        'url' => url('/hourly-finishing-production-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Hourly Finishing Production Dashboard',
                                        'url' => url('/hourly-finishing-production-report/dashboard'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                    [
                                        'title' => 'Finishing Production Report V2',
                                        'url' => url('/finishing-production-report-v2'),
                                        'icon' => '',
                                        'items' => []
                                    ],
                                    [
                                        'title' => 'Finishing Production Report V3',
                                        'url' => url('/finishing-production-report/v3'),
                                        'icon' => '',
                                        'items' => []
                                    ],
                                    [
                                        'title' => 'Monthly Total Received Finishing Report',
                                        'url' => url('/monthly-total-received-finishing-report'),
                                        'icon' => '',
                                        'items' => [],
                                    ],
                                ],
                            ],
                        ]
                    ],
                    [
                        'title' => 'Production',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Production Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Date Wise Cutting Report',
                                        'url' => url('/manual-date-wise-cutting-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Style Overall Summary Report',
                                        'url' => url('/manual-style-overall-summary-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Date Wise Print/Embr. Summary Report',
                                        'url' => url('/manual-date-wise-print-embr-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Challan Wise Embr. Summary Report',
                                        'url' => url('/manual-challan-wise-embr-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Challan Wise Print Summary Report',
                                        'url' => url('/manual-challan-wise-print-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Challan Wise Style Input Summary',
                                        'url' => url('/manual-challan-wise-style-input-summary'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Daily Input Unit Wise Report',
                                        'url' => url('/manual-daily-input-unit-wise-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Floor Size Wise Style In Out Summary Report',
                                        'url' => url('/manual-floor-size-wise-style-in-out-summary'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Floor Wise Style In Out Summary',
                                        'url' => url('/floor-wise-style-in-out-summary'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Hourly Production Report',
                                        'url' => url('/manual-date-floor-wise-hourly-sewing-output'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Daily Sewing Production Report',
                                        'url' => url('/daily-sewing-production-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Color Wise Date Wise Sewing Report',
                                        'url' => url('/buyer-style-color-wise-daily-sewing-output-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Style Wise Rejection Report',
                                        'url' => url('/style-wise-rejection-report'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Yearly Rejection Summary Report',
                                        'url' => url('/yearly-rejection-summary-report'),
                                        'icon' => '',
                                    ],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
            [
                'title' => 'SUBCONTRACT',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Textile Subcontract Reports',
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
                ]
            ],
            [
                'title' => 'ACCOUNTING',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'New Accounting & Finance',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'New Acc & Fin Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Transactions',
                                        'url' => url('finance/transactions'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Ledger',
                                        'url' => url('finance/ledger-v2'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Group Ledger',
                                        'url' => url('finance/group-ledger'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Trial Balance',
                                        'url' => url('finance/trial-balance-v2'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Income Statement',
                                        'url' => url('finance/income-statement'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Balance Sheet',
                                        'url' => url('finance/balance-sheet'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Receipt Payment Report',
                                        'url' => url('finance/receipts-and-payments'),
                                        'icon' => ''
                                    ],
                                    [
                                        'title' => 'Month Wise Receipt Payment Report',
                                        'url' => url('finance/month-wise-receipt-payment-report'),
                                        'icon' => ''
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'title' => 'Accounting & Finance',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Voucher Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Vouchers List',
                                        'icon' => '',
                                        'url' => url('basic-finance/vouchers'),
                                    ],
                                    [
                                        'title' => 'Vouchers Approve Panel',
                                        'icon' => '',
                                        'url' => url('basic-finance/vouchers-approve-panels'),
                                    ],
                                    [
                                        'title' => 'Transaction Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Cash Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Budget Vs Variance',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Cash Book',
                                        'icon' => '',
                                        'url' => url('basic-finance/cash-management/cash-book'),
                                    ],
                                    [
                                        'title' => 'Detailed Cash Book',
                                        'icon' => '',
                                        'url' => url('basic-finance/cash-management/detailed-cash-book'),
                                    ],
                                    [
                                        'title' => 'Receipt And Payment Statement',
                                        'url' => url('basic-finance/cash-receipts-and-payments'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Project Wise Cash Book',
                                        'url' => url('basic-finance/cash-management/project-wise-cash-books'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Cash Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Cash Certificate',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Deposit Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Payment Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Bank Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Realization MIS Report',
                                        'icon' => '',
                                        'url' => url('basic-finance/accounting-realization/mis-report'),
                                    ],
                                    [
                                        'title' => 'Accounts Wise Balance Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'List of Transaction',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Bank Book',
                                        'icon' => '',
                                        'url' => url('basic-finance/bank-management/bank-book'),
                                    ],
                                    [
                                        'title' => 'Detailed Bank Book',
                                        'icon' => '',
                                        'url' => url('basic-finance/bank-management/detailed-bank-book'),
                                    ],
                                    [
                                        'title' => 'Receipt And Payment Statement',
                                        'url' => url('basic-finance/bank-receipts-and-payments'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Deposit Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Payment Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Outstanding Position',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Clear Cheque List',
                                        'icon' => '',
                                        'url' => url('basic-finance/clear-cheque-list'),
                                    ],
                                    [
                                        'title' => 'Unclear Cheque List',
                                        'icon' => '',
                                        'url' => url('basic-finance/unclear-cheque-list'),
                                    ],
                                    [
                                        'title' => 'FDR Schedule',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'AIT Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Void Cheque Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Type Wise Loan Schedule',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Limit Vs. Actual',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Vendor Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Supplier Balance Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'AR/ Aging Summary/Details',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Payment Report',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Average Days to Pay Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Customer Contract List',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Open Bills',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Schedule Payment',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Supplier Wise Tax Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Supplier Wise Tax Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Supplier Wise Vat Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Supplier Wise Vat Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Tax Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Vat Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Purchase Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Purchase Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Purchase Return Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Work order List',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Comparative Statement List',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Purchase by Vendor',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Purchase By Item',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Purchase By Project',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ], [
                                        'title' => 'Close purchase oder by Vendor',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ], [
                                        'title' => 'Open purchase oder by Vendor',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ], [
                                        'title' => 'Expired purchased Order list',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ], [
                                        'title' => 'Item wise Purchased Leger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ], [
                                        'title' => 'Supplier Wise Purchase Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Customer Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Customer Balance Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'AR/ Aging Summary/Details',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Collection Report',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Average Days to Pay Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Customer Contract List',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Open Invoice',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Schedule Received',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Sales Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Sales by Customer',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Sales By Item',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Sales By Project',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Sales By Marketing',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Close Sales oder by customer',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Open Sales oder by customer',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Sales Gap',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Expired Order list',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Item wise Sales Leger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Customer Wise Sales Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Assets Manager Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Assets Log Book',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Asset Register',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Item Wise Assets Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Asset Group Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Inventories Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Inventory Valuation Summary',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Item Wise Stock Report',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Item Wise Consumption Report',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Physical Inventory Worksheet',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Work In Progress Report',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Supplier Wise Goods Receipt',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Item Wise Goods Receipt',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Cost & Budget Management Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Fixed Cost',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Variable Cost',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Post Cost',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Pre-Cost Vs Post Cost Analysis',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Break Even Sales',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Budget Vs Actual',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Budget Overview',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Ratio Analysis Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Current Ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/current-ratio'),
                                    ],
                                    [
                                        'title' => 'Quick Ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/quick-ratio'),
                                    ],
                                    [
                                        'title' => 'Working Capital Ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/working-capital-ratio'),
                                    ],
                                    [
                                        'title' => 'Debt to Equity Ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/debt-to-equity-ratio'),
                                    ],
                                    [
                                        'title' => 'Equity ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/equity-ratio'),
                                    ],
                                    [
                                        'title' => 'Debit ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/debt-ratio'),
                                    ],
                                    [
                                        'title' => 'Account Receivable Turnover',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/account-receivable-turnover-ratio'),
                                    ],
                                    [
                                        'title' => 'Days sales Outstanding',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/days-sales-outstanding-ratio'),
                                    ],
                                    [
                                        'title' => 'Asset Turnover',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/asset-turnover-ratio'),
                                    ],
                                    [
                                        'title' => 'Inventory Turnover',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/inventory-turnover-ratio'),
                                    ],
                                    [
                                        'title' => 'Days Sales In Inventory',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/days-sales-in-inventory-ratio'),
                                    ],
                                    [
                                        'title' => 'Accounts Payable Turnover',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/accounts-payable-turnover-ratio'),
                                    ],
                                    [
                                        'title' => 'Gross Profit Ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/gross-profit-ratio'),
                                    ],
                                    [
                                        'title' => 'Net Profit Ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/net-profit-ratio'),
                                    ],
                                    [
                                        'title' => 'Return on Assets Ratio (ROA)',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/return-on-assets-ratio'),
                                    ],
                                    [
                                        'title' => 'Return On Capital Employeed (ROCE)',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/return-on-capital-employeed-ratio'),
                                    ],
                                    [
                                        'title' => 'Return On Equity (ROE)',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/return-on-equity-ratio'),
                                    ],
                                    [
                                        'title' => 'Earning Per Share (EPS)',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/earning-per-share-ratio'),
                                    ],
                                    [
                                        'title' => 'Price Earnings Ratio',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/price-earnings-ratio'),
                                    ],
                                    [
                                        'title' => 'Fixed Charge Coverage Ratio (FCCR)',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/fixed-charge-coverage-ratio'),
                                    ],
                                    [
                                        'title' => 'Debt Service Coverage Ratio (DSCR)',
                                        'icon' => '',
                                        'url' => url('basic-finance/ratio-report/debt-service-coverage-ratio'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Ac Reports',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Voucher List',
                                        'url' => url('basic-finance/vouchers'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Transactions',
                                        'url' => url('basic-finance/transactions'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Ledger',
                                        'url' => url('basic-finance/ledger-v3'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Group Ledger',
                                        'url' => url('basic-finance/group-ledger'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Provisional Ledger',
                                        'url' => url('basic-finance/provisional-ledger'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Receipt And Payment Statement',
                                        'url' => url('basic-finance/all-receipts-and-payments'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Trial Balance',
                                        'url' => url('basic-finance/trial-balance'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Income Statement',
                                        'url' => url('basic-finance/income-statement'),
                                        'icon' => '',
                                    ],
                                    [
                                        'title' => 'Balance Sheet',
                                        'url' => url('basic-finance/balance-sheet'),
                                        'icon' => '',
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Financial Reporting Reports',
                                'icon' => '',
                                'url' => '',
                                'items' => [
                                    [
                                        'title' => 'Police Notes',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Notes for financial statements',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Group Summary Report',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Control Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Ledger',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Trial Balance',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Receive/Payment',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Statement of Cash Flows',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Statement of Changes in Equity',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Statement of Financial Position',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                    [
                                        'title' => 'Asset Schedule',
                                        'icon' => '',
                                        'url' => url('basic-finance/maintenance'),
                                    ],
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'HRM',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Attendance Reports',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Monthly Attendance',
                                'icon' => '',
                                'url' => url('/hr/attendance/report/monthly-attendance'),
                            ],
                            [
                                'title' => 'Monthly Attendance & OT',
                                'icon' => '',
                                'url' => url('/hr/attendance/report/monthly-attendance-v2'),
                            ],
                            [
                                'title' => 'Daily Attendance',
                                'icon' => '',
                                'url' => url('/hr/attendance/report/daily-attendance'),
                            ],
                            [
                                'title' => 'Daily Attendance Report',
                                'icon' => '',
                                'url' => url('/hr/attendance/report/daily-attendence-report'),
                            ],
                            [
                                'title' => 'Daily Workers Absent',
                                'icon' => '',
                                'url' => url('/hr/attendance/report/daily-workers-absent-report'),
                            ],
                        ]
                    ],
                    [
                        'title' => 'Leave Reports',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Individual Leaves',
                                'icon' => '',
                                'url' => url('/hr/leave/reports/individual-leave-report'),
                            ],
                            [
                                'title' => 'Yearly Leaves',
                                'icon' => '',
                                'url' => url('/'),
                            ],
                            [
                                'title' => 'Monthly Leaves',
                                'icon' => '',
                                'url' => url('/'),
                            ],
                        ]
                    ],
                    [
                        'title' => 'Payroll Reports',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Monthly Pay Sheet',
                                'icon' => '',
                                'url' => url('/'),
                            ],
                            [
                                'title' => 'Monthly Pay Summary',
                                'icon' => '',
                                'url' => url('/'),
                            ],
                            [
                                'title' => 'Holiday Pay Sheet',
                                'icon' => '',
                                'url' => url('/'),
                            ],
                            [
                                'title' => 'Extra OT Sheet',
                                'icon' => '',
                                'url' => url('/'),
                            ],
                            [
                                'title' => 'Bank Salary Sheet',
                                'icon' => '',
                                'url' => url('/'),
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'ADD-ONS',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'TQM Reports',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'DHU Report',
                                'url' => url('/dhu-report'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Factory DHU Report Cumulative',
                                'url' => url('/factory-dhu-cumulative-report'),
                                'icon' => '',
                                'items' => [],
                            ],
                            [
                                'title' => 'Factory DHU Report Daily',
                                'url' => url('/factory-dhu-daily-report'),
                                'icon' => '',
                                'items' => [],
                            ],
                        ]
                    ],
                    [
                        'title' => 'Warehouse Management Reports',
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
                    [
                        'title' => 'Leftover Droplets',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Leftover Fabric Daily Report',
                                'icon' => '',
                                'url' => '#',
                            ],
                            [
                                'title' => 'GMT Send & Rcv Daily Summary Report',
                                'icon' => '',
                                'url' => '#',
                            ],
                            [
                                'title' => 'GMT Booking Color Wise Send & Rcv Report',
                                'icon' => '',
                                'url' => '#',
                            ],
                            [
                                'title' => 'Finish GMT Delivery & Sales Daily Report',
                                'icon' => '',
                                'url' => '#',
                            ],
                            [
                                'title' => 'Finish GMT Daily Summary Report',
                                'icon' => '',
                                'url' => '#',
                            ],
                        ]
                    ],
                    [
                        'title' => 'Print/Embr. Factory',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Date Wise Report',
                                'icon' => '',
                                'url' => '#',
                            ],
                        ]
                    ],
                    [
                        'title' => 'Lab',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Daily Work Sheet Report',
                                'icon' => '',
                                'url' => '#',
                            ],
                        ]
                    ],
                ]
            ],
        ]
    ],
    // MIS END // MIS END // MIS END // MIS END // MIS END // MIS END // MIS END // MIS END // MIS END // MIS END


    // Add-ONS // Add-ONS // Add-ONS // Add-ONS // Add-ONS // Add-ONS // Add-ONS // Add-ONS // Add-ONS // Add-ONS
    [
        'title' => 'ADD-ONS',
        'priority' => 15000,
        'url' => '',
        'icon' => '&#xe87b;',
        'items' => [
            [
                'title' => 'TQM',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Cutting DHU Entry',
                        'url' => url('/cutting-dhu/create'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Sewing DHU Entry',
                        'url' => url('/sewing-dhu/create'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Finishing DHU Entry',
                        'url' => url('/finishing-dhu/create'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'Defects',
                        'url' => url('/tqm-defects'),
                        'icon' => '',
                        'items' => [],
                    ],
                    [
                        'title' => 'DHU levels',
                        'url' => url('/tqm-dhu-levels'),
                        'icon' => '',
                        'items' => [],
                    ],
                ],
            ],
            [
                'title' => 'Warehouse Management',
                'url' => '',
                'icon' => '',
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
                ]
            ],
            [
                'title' => 'Leftover Droplets',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Fabric Send & Receive',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Fabric Delivery & Sales',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Garments Send & Receive',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Garments Delivery',
                        'icon' => '',
                        'url' => '#',
                    ],
                ]
            ],
            [
                'title' => 'Incentive Droplets',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Job Classes',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Operations',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Operational Conditions',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Purchase Order Wise Operation',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Employees',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Employee Wise Target',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Cupon Generation',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Date Wise Incentive Report',
                        'icon' => '',
                        'url' => '#',
                    ],
                ]
            ],
            [
                'title' => 'Print/Embr. Factory',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Print/Embr Factory Receive Scan',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Print/Embr Production Scan',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Print/Embr QC Scan',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Print/Embr Factory Receive Challan List',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Print/Embr Factory Receive Tag List',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Print/Embr Qc Tag List',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Print Embr Delivery Challan List',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Print/Embroidery Target',
                        'icon' => '',
                        'url' => '#',
                    ],
                ]
            ],
            [
                'title' => 'Lab',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Fabric Lab',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Requisitions',
                                'icon' => '',
                                'url' => '#',
                            ],
                            [
                                'title' => 'Work Sheet',
                                'icon' => '',
                                'url' => '#',
                            ],
                        ]
                    ],
                    [
                        'title' => 'Garments Lab',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Requisition',
                                'icon' => '',
                                'url' => '#',
                            ],
                            [
                                'title' => 'Work Sheet',
                                'icon' => '',
                                'url' => '#',
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'IT Droplets',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'IT Item Receive Manual',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'IT Item Delevery Manual',
                        'icon' => '',
                        'url' => '#',
                    ],
                    [
                        'title' => 'IT Item wastage Manual',
                        'icon' => '',
                        'url' => '#',
                    ],
                ]
            ],
            [
                'title' => 'Security Control',
                'icon' => '',
                'url' => '',
                'items' => [

                    [
                        'title' => 'Vehicle Tracking System',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'In house Vehicle Settings',
                                'icon' => '',
                                'url' => url('/vehicle-system'),
                            ],
                            [
                                'title' => 'In house Vehicle Assign',
                                'icon' => '',
                                'url' => url('/vehicle-assign-system'),
                            ],
                            [
                                'title' => 'Third party Vehicle Tracking',
                                'icon' => '',
                                'url' => url('/third-party-vehicle'),
                            ]
                        ]
                    ],
                    [
                        'title' => 'Employee Tracking System',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                            'title' => 'employee-settings',
                            'icon' => '',
                            'url' => url('/employee-system'),
                            ]

                        ]
                    ],
                    [
                        'title' => 'Visitor Tracking System',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Visitor Settings',
                                'icon' => '',
                                'url' => url('/visitor-system'),
                            ]
                        ]
                    ],

                ]
            ],
        ]
    ],
    // Add-ONS END // Add-ONS END // Add-ONS END // Add-ONS END // Add-ONS END // Add-ONS END // Add-ONS END


    // SETTINGS // SETTINGS // SETTINGS // SETTINGS // SETTINGS // SETTINGS // SETTINGS // SETTINGS // SETTINGS
    [
        'title' => 'SETTINGS',
        'priority' => 16000,
        'url' => '',
        'icon' => '&#xe8b8;',
        'items' => [
            [
                'title' => 'General',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'General Settings',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Group',
                                'icon' => '',
                                'url' => url('/companies'),
                            ],
                            [
                                'title' => 'Section',
                                'icon' => '',
                                'url' => url('/section'),
                            ],
                            [
                                'title' => 'Factories',
                                'icon' => '',
                                'url' => url('/factories'),
                            ],
                            [
                                'title' => 'Roles',
                                'icon' => '',
                                'url' => url('/roles'),
                            ],
                            [
                                'title' => 'Approvals Name',
                                'icon' => '',
                                'url' => url('/permissions'),
                            ],
                            [
                                'title' => 'Modules',
                                'icon' => '',
                                'url' => url('/modules-data'),
                            ],
                            [
                                'title' => 'Menus',
                                'icon' => '',
                                'url' => url('/menus'),
                            ],
                            [
                                'title' => 'Assign Full Permission',
                                'icon' => '',
                                'url' => url('/assign-module-wise-full-permission'),
                            ],
                            [
                                'title' => 'Assign Permission',
                                'icon' => '',
                                'url' => url('/assign-permissions'),
                            ],
                            [
                                'title' => 'Departments',
                                'icon' => '',
                                'url' => url('/departments'),
                            ],
                            [
                                'title' => 'Users',
                                'icon' => '',
                                'url' => url('/users'),
                            ],
                            [
                                'title' => 'Logs',
                                'icon' => '',
                                'url' => url('/logs'),
                            ],
                            [
                                'title' => 'Audit',
                                'icon' => '',
                                'url' => url('/audit-log-book'),
                            ],
                            [
                                'title' => 'Localization',
                                'icon' => '',
                                'url' => url('/localizations'),
                            ],
                            [
                                'title' => 'Mail',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Mail Employee List',
                                        'icon' => '',
                                        'url' => url('/mail-employee-list'),
                                    ],
                                    [
                                        'title' => 'Mail Configuration',
                                        'icon' => '',
                                        'url' => url('/mail-configuration'),
                                    ],
                                    [
                                        'title' => 'Mail Group',
                                        'icon' => '',
                                        'url' => url('/mail-group'),
                                    ],
                                    [
                                        'title' => 'Mail Setting',
                                        'icon' => '',
                                        'url' => url('/mail-setting'),
                                    ],
                                    [
                                        'title' => 'Mail Signature',
                                        'icon' => '',
                                        'url' => url('/mail-signature'),
                                    ],
                                ]
                            ],
                            [
                                'title' => 'Notification',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Notification Group',
                                        'icon' => '',
                                        'url' => url('/notification-group'),
                                    ],
                                    [
                                        'title' => 'Notification Setting',
                                        'icon' => '',
                                        'url' => url('/notification-setting'),
                                    ],
                                ]
                            ],
                        ],
                    ],
                    [
                        'title' => 'System Variables',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Merchandising',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'title' => 'Merchandising Variable Settings',
                                        'icon' => '',
                                        'url' => url('/merchandising_variable_settings'),
                                    ],
                                    [
                                        'title' => 'Po File Issue Settings',
                                        'icon' => '',
                                        'url' => url('/po_file_issue_settings'),
                                    ],
                                    [
                                        'title' => 'Commercial Cost Method Settings',
                                        'icon' => '',
                                        'url' => url('/commercial-cost-method-in-pq'),
                                    ],
                                    [
                                        'title' => 'Short Bookings Settings',
                                        'icon' => '',
                                        'url' => url('/short-bookings-settings'),
                                    ],
                                    [
                                        'title' => 'User Wise Buyer Permissions',
                                        'icon' => '',
                                        'url' => url('/user-wise-buyer-permission-list'),
                                    ],
                                    [
                                        'title' => 'Page Wise View Permission',
                                        'icon' => '',
                                        'url' => url('/page-wise-view-permission'),
                                    ],
                                    [
                                        'title' => 'Report Signature',
                                        'icon' => '',
                                        'url' => url('/report-signature'),
                                    ],
                                    [
                                        'title' => 'Hide Fields Variable',
                                        'icon' => '',
                                        'url' => url('/hide-fields-variable')
                                    ],
                                    [
                                        'title' => 'Trims Sensitivity Validation',
                                        'icon' => '',
                                        'url' => url('/trims-sensitivity-variables')
                                    ],
                                ],

                            ],
                            [
                                'title' => 'Inventory',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'icon' => '',
                                        'title' => 'Fabric Store Variable',
                                        'url' => url('/fabric-store-variable-settings'),
                                    ],
                                    [
                                        'icon' => '',
                                        'title' => 'Yarn Store Variable',
                                        'url' => url('/yarn-store-variable-settings'),
                                    ],
                                    [
                                        'icon' => '',
                                        'title' => 'Dyes Chemical Store Variable',
                                        'url' => url('/dyes-chemical-store-variable-settings'),
                                    ],
                                    [
                                        'icon' => '',
                                        'title' => 'Service Company/Party',
                                        'url' => url('/service-company'),
                                    ]
                                ],
                            ],
                            [
                                'title' => 'Accounting',
                                'url' => '',
                                'icon' => '',
                                'items' => [
                                    [
                                        'icon' => '',
                                        'title' => 'Accounting Variable',
                                        'url' => url('/accounting-variable-settings'),
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Application Variables',
                                'icon' => '',
                                'url' => url('/garments-production-entry'),
                            ],
                        ],
                    ],

                ],
            ],
            [
                'title' => 'Merchandising',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Buyer & Supplier Profile',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Buyers',
                                'icon' => '',
                                'url' => url('/buyers'),
                            ],
                            [
                                'title' => 'Buying Agent',
                                'icon' => '',
                                'url' => url('/buying-agent'),
                            ],
                            [
                                'title' => 'Buying Agent Merchant',
                                'icon' => '',
                                'url' => url('/buying-agent-merchant'),
                            ],
                            [
                                'title' => 'Suppliers',
                                'icon' => '',
                                'url' => url('/suppliers'),
                            ],
                        ],
                    ],
                    [
                        'title' => 'Item & Season Details',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Item',
                                'icon' => '',
                                'url' => url('/items'),
                            ],
                            [
                                'title' => 'Item Subgroup',
                                'icon' => '',
                                'url' => url('/item-subgroups'),
                            ],
                            [
                                'title' => 'Item Group',
                                'icon' => '',
                                'url' => url('/item-group'),
                            ],
                            [
                                'title' => 'Item Creations',
                                'icon' => '',
                                'url' => url('/item-creations'),
                            ],
                            [
                                'title' => 'Seasons',
                                'icon' => '',
                                'url' => url('/seasons'),
                            ],
                            [
                                'title' => 'Care Label Types',
                                'icon' => '',
                                'url' => url('/care-label-types'),
                            ],
                            [
                                'title' => 'Group Wise Fields',
                                'icon' => '',
                                'url' => url('/group-wise-fields')
                            ],
                        ],
                    ],
                    [
                        'title' => 'Yarn & Fabric Details',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Yarn Counts',
                                'icon' => '',
                                'url' => url('/yarn-counts'),
                            ],
                            [
                                'title' => 'Yarn Compositions',
                                'icon' => '',
                                'url' => url('/yarn-compositions'),
                            ],
                            [
                                'title' => 'Yarn Type',
                                'icon' => '',
                                'url' => url('/composition-types'),
                            ],
                            [
                                'title' => 'Fabric Nature',
                                'icon' => '',
                                'url' => url('/fabric-natures'),
                            ],
                            [
                                'title' => 'Fabric Name Entry',
                                'icon' => '',
                                'url' => url('/fabric-construction-entry'),
                            ],
                            [
                                'title' => 'Fabric Compositions',
                                'icon' => '',
                                'url' => url('/fabric-compositions'),
                            ],
                            [
                                'title' => 'Color Type',
                                'icon' => '',
                                'url' => url('/color-types'),
                            ],
                            [
                                'title' => 'Color Range',
                                'icon' => '',
                                'url' => url('/color-ranges'),
                            ],
                        ],
                    ],
                    [
                        'title' => 'Garments Details',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'Garments Item Group',
                                'icon' => '',
                                'url' => url('/garments-item-group'),
                            ],
                            [
                                'title' => 'Garments Item',
                                'icon' => '',
                                'url' => url('/garments-items'),
                            ],
                            [
                                'title' => 'Garments Sample',
                                'icon' => '',
                                'url' => url('/garments-sample'),
                            ],
                            [
                                'title' => 'Product Department',
                                'icon' => '',
                                'url' => url('/product-department'),
                            ],
                            [
                                'title' => 'Product Category',
                                'icon' => '',
                                'url' => url('/product-category'),
                            ],
                            [
                                'title' => 'Body Part',
                                'icon' => '',
                                'url' => url('/body-parts'),
                            ],
                            [
                                'title' => 'Part Types',
                                'icon' => '',
                                'url' => url('/party-types'),
                            ],
                            [
                                'title' => 'Color',
                                'icon' => '',
                                'url' => url('/colors'),
                            ],

                            [
                                'title' => 'Size',
                                'icon' => '',
                                'url' => url('/sizes'),
                            ],
                            [
                                'title' => 'Fabric Process',
                                'icon' => '',
                                'url' => url('/processes'),
                            ],
                        ],
                    ],
                    [
                        'title' => 'Team',
                        'icon' => '',
                        'url' => url('/teams'),
                    ],
                    [
                        'title' => 'Unit of Measurement',
                        'icon' => '',
                        'url' => url('/unit-of-measurements'),
                    ],
                    [
                        'title' => 'Incoterms',
                        'icon' => '',
                        'url' => url('/incoterms'),
                    ],
                    [
                        'title' => 'Currency',
                        'icon' => '',
                        'url' => url('/currencies'),
                    ],

                    [
                        'title' => 'Store',
                        'icon' => '',
                        'url' => url('/stores'),
                    ],

                    [
                        'title' => 'Product Type',
                        'icon' => '',
                        'url' => url('/product-types'),
                    ],

                    [
                        'title' => 'Trims and Accessories',
                        'icon' => '',
                        'url' => url('/trims-accessories-item'),
                    ],

                    [
                        'title' => 'Care Instructions',
                        'icon' => '',
                        'url' => url('/care-instructions'),
                    ],

                    [
                        'title' => 'Financial Parameter Setup',
                        'icon' => '',
                        'url' => url('/financial-parameter-setups'),
                    ],
                    [
                        'title' => 'Embellishment Items',
                        'icon' => '',
                        'url' => url('/embellishment-items'),
                    ],
                    [
                        'title' => 'Audit',
                        'icon' => '',
                        'url' => url('/audits'),
                    ],
                    [
                        'title' => 'Terms And Conditions',
                        'icon' => '',
                        'url' => url('/terms-conditions'),
                    ],
                    [
                        'title' => 'Archive File',
                        'url' => url('/archive-file'),
                        'icon' => '',
                    ],
                ],
            ],
            [
                'title' => 'Sample',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sample Floor',
                        'icon' => '',
                        'url' => url('sample-floor'),
                    ],
                    [
                        'title' => 'Sample Line',
                        'icon' => '',
                        'url' => url('sample-line'),
                    ],

                ],
            ],
            [
                'title' => 'Protracker',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Parts',
                        'icon' => '',
                        'url' => url('/parts'),
                    ],
                    [
                        'title' => 'Types',
                        'icon' => '',
                        'url' => url('/types'),
                    ],
                    [
                        'title' => 'Lots',
                        'icon' => '',
                        'url' => url('/lots')
                    ],
                    [
                        'title' => 'Others Factories',
                        'icon' => '',
                        'url' => url('/others-factories'),
                    ],
                    [
                        'title' => 'Print Factory Tables',
                        'icon' => '',
                        'url' => url('/print-factory-tables'),
                    ],
                    [
                        'title' => 'Cutting Floor',
                        'icon' => '',
                        'url' => url('/cutting-floors'),
                    ],
                    [
                        'title' => 'Cutting Table',
                        'icon' => '',
                        'url' => url('/cutting-tables'),
                    ],
                    [
                        'title' => 'Sewing Floor',
                        'icon' => '',
                        'url' => url('/floors'),
                    ],
                    [
                        'title' => 'Sewing Lines',
                        'icon' => '',
                        'url' => url('/lines'),
                    ],
                    [
                        'title' => 'Production Date Change',
                        'icon' => '',
                        'url' => url('/production-date-change'),
                    ],
                    [
                        'title' => 'Sewing Line Tasks',
                        'icon' => '',
                        'url' => url('/tasks'),
                    ],
                    [
                        'title' => 'Machine Types',
                        'icon' => '',
                        'url' => url('/machine-types'),
                    ],
                    [
                        'title' => 'Operator Skills',
                        'icon' => '',
                        'url' => url('/operator-skill'),
                    ],
                    [
                        'title' => 'Guide Or Folders',
                        'icon' => '',
                        'url' => url('/guide-or-folders')
                    ],
                    [
                        'title' => 'Finishing Floor',
                        'icon' => '',
                        'url' => url('/finishing-floor'),
                    ],
                    [
                        'title' => 'Finishing Table',
                        'icon' => '',
                        'url' => url('/finishing-table'),
                    ],
                ],
            ],
            [
                'title' => 'Planning',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Item Category',
                        'icon' => '',
                        'url' => url('/planning/settings/item-categories'),
                    ],
                    [
                        'title' => "Buyer's Capacity",
                        'icon' => '',
                        'url' => url('/planning/settings/buyers-capacity'),
                    ],
                ],
            ],
            [
                'title' => 'Knitracker',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Yarn Types',
                        'icon' => '',
                        'url' => url('/yarn-types'),
                    ],
                    [
                        'title' => 'Parties',
                        'icon' => '',
                        'url' => url('/parties'),
                    ],
                    [
                        'title' => 'Shifts',
                        'icon' => '',
                        'url' => url('/shifts'),
                    ],
                    [
                        'title' => 'Designations',
                        'icon' => '',
                        'url' => url('/designations'),
                    ],
                    [
                        'title' => 'Operators',
                        'icon' => '',
                        'url' => url('/operators'),
                    ],
                    [
                        'title' => 'Knitting Floor',
                        'icon' => '',
                        'url' => url('/knitting-floor'),
                    ],
                    [
                        'title' => 'Brands',
                        'icon' => '',
                        'url' => url('/brands'),
                    ],
                    [
                        'title' => 'Machine Type',
                        'icon' => '',
                        'url' => url('/knit-machine-types'),
                    ],
                    [
                        'title' => 'Machines',
                        'icon' => '',
                        'url' => url('/machines'),
                    ],
                    [
                        'title' => 'Fabric Type',
                        'icon' => '',
                        'url' => url('/fabric-types'),
                    ],
                    [
                        'title' => 'Knit Fabric Grade',
                        'icon' => '',
                        'url' => url('/knit_fabric_grade_settings'),
                    ],
                    [
                        'title' => 'Knit Fabric Fault',
                        'icon' => '',
                        'url' => url('/knit_fabric_fault_settings'),
                    ],
                    [
                        'title' => 'Production Variable',
                        'icon' => '',
                        'url' => url('/knitting-production-variable'),
                    ],
                ],
            ],
            [
                'title' => 'Commercial',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Lien',
                        'icon' => '',
                        'url' => url('commercial/lien'),
                    ],
                    [
                        'title' => 'Lien Bank',
                        'icon' => '',
                        'url' => url('/lien-banks'),
                    ],
                    [
                        'title' => 'Bonded Warehouse',
                        'icon' => '',
                        'url' => url('commercial/bonded-warehouse'),
                    ],
                    [
                        'title' => 'Mailing Variable',
                        'icon' => '',
                        'url' => url('commercial-mailing-variable-settings'),
                    ],
                    [
                        'title' => 'Variable',
                        'icon' => '',
                        'url' => url('/commercial/commercial-variable/create'),
                    ],
                ],
            ],
            [
                'title' => 'Inventory',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'General Store',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Item Category',
                                'icon' => '',
                                'url' => url('/general-store/items-category'),
                            ],
                            [
                                'title' => 'Items',
                                'icon' => '',
                                'url' => url('/general-store/items'),
                            ],
                            [
                                'title' => 'Uom',
                                'icon' => '',
                                'url' => url('/general-store/uom'),
                            ],
                            [
                                'title' => 'Brand',
                                'icon' => '',
                                'url' => url('/general-store/brands'),
                            ],
                            [
                                'title' => 'Rack',
                                'icon' => '',
                                'url' => url('/general-store/racks'),
                            ],
                            [
                                'title' => 'Customer',
                                'icon' => '',
                                'url' => url('/general-store/customers'),
                            ],
                        ],
                    ],
                    [
                        'title' => 'Dyes Store',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Item Category',
                                'icon' => '',
                                'url' => url('/dyes-store/items-category'),
                            ],
                            [
                                'title' => 'Items',
                                'icon' => '',
                                'url' => url('/dyes-store/items'),
                            ],
                            [
                                'title' => 'Uom',
                                'icon' => '',
                                'url' => url('/dyes-store/uom'),
                            ],
                            [
                                'title' => 'Brand',
                                'icon' => '',
                                'url' => url('/dyes-store/brands'),
                            ],
                            [
                                'title' => 'Store',
                                'icon' => '',
                                'url' => url('/dyes-store/stores'),
                            ],
                            [
                                'title' => 'Rack',
                                'icon' => '',
                                'url' => url('/dyes-store/racks'),
                            ],
                            [
                                'title' => 'Customer',
                                'icon' => '',
                                'url' => url('/dyes-store/customers'),
                            ],
                            [
                                'title' => 'Storage Location',
                                'icon' => '',
                                'url' => url('/dyes-store/storage-location'),
                            ],
                            [
                                'title' => 'Department',
                                'icon' => '',
                                'url' => url('/dyes-store/department'),
                            ],
                        ]
                    ],
                    [
                        'title' => 'Store Managements',
                        'icon' => '',
                        'url' => url('/inventory/store-managements'),
                    ],
                ],
            ],
            [
                'title' => 'Subcontract',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Textile Subcontract',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Subcontract Processes',
                                'icon' => '',
                                'url' => url('/subcontract/process'),
                            ],
                            [
                                'title' => 'Grey Store Entry',
                                'icon' => '',
                                'url' => url('/subcontract/sub-grey-store'),
                            ],
                            [
                                'title' => 'Sub Dyeing Units',
                                'icon' => '',
                                'url' => url('/subcontract/sub-dyeing-unit'),
                            ],
                            [
                                'title' => 'Sub Dyeing Machines',
                                'icon' => '',
                                'url' => url('/subcontract/dyeing-machine'),
                            ],
                            [
                                'title' => 'Dyeing Recipe Operation Entry',
                                'icon' => '',
                                'url' => url('/subcontract/sub-dyeing-recipe-operation'),
                            ],
                            [
                                'title' => 'Dyeing Operation Function Entry',
                                'icon' => '',
                                'url' => url('/subcontract/sub-dyeing-operation-function'),
                            ],
                        ]
                    ],
                    [
                        'title' => 'SubContract Variables',
                        'url' => '',
                        'icon' => '',
                        'items' => [
                            [
                                'title' => 'SubContract Variable Settings',
                                'icon' => '',
                                'url' => url('/subcontract/sub-dyeing-variable'),
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Dyeing',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Dyeing Company',
                        'icon' => '',
                        'url' => url('dyeing-company'),
                    ],

                ],
            ],
            [
                'title' => 'HRM',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Department',
                        'icon' => '',
                        'url' => url('/hr/departments'),
                    ],
                    [
                        'title' => 'Sections',
                        'icon' => '',
                        'url' => url('/hr/sections'),
                    ],
                    [
                        'title' => 'Designations',
                        'icon' => '',
                        'url' => url('/hr/designations'),
                    ],

                    [
                        'title' => 'Groups',
                        'icon' => '',
                        'url' => url('/hr/groups'),
                    ],

                    [
                        'title' => 'Grades',
                        'icon' => '',
                        'url' => url('/hr/grades'),
                    ],

                    [
                        'title' => 'Leave Settings',
                        'icon' => '',
                        'url' => url('/hr/leave-settings'),
                    ],

                    [
                        'title' => 'Holiday',
                        'icon' => '',
                        'url' => url('/hr/holidays'),
                    ],

                    [
                        'title' => 'Banks',
                        'icon' => '',
                        'url' => url('/hr/banks'),
                    ],

                    [
                        'title' => 'Shifts',
                        'icon' => '',
                        'url' => url('/hr/shifts'),
                    ],
                    [
                        'title' => 'Office Time Settings',
                        'icon' => '',
                        'url' => url('/hr/office-time-settings'),
                    ],

                ]
            ],
            [
                'title' => 'Machine Settings',
                'url' => '',
                'icon' => '',
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
                'title' => 'Fin & Accounting',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Entry',
                        'icon' => '',
                        'url' => '',
                        'items' => [
                            [
                                'title' => 'Users',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'Unit',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'Department',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'Item Groups',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'Item',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'UOM',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'Roles',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'Authorization Panel',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                            [
                                'title' => 'Permission',
                                'icon' => '',
                                'url' => url('basic-finance/maintenance'),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    // SETTINGS END // SETTINGS END // SETTINGS END // SETTINGS END // SETTINGS END // SETTINGS END // SETTINGS END
];
