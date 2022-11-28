<?php

// sample menu

return [
    [
        'title' => 'SYSTEM SETTINGS',
        'priority' => 7000,
        'url' => '',
        'icon' => '&#xe1bd;',
        'class' => 'nav-header hidden-folded',
        'items' => []
    ],
    [
        'title' => 'General Settings',
        'priority' => 7001,
        'url' => '',
        'icon' => '&#xe8b8;',
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
        'title' => 'Merchandising',
        'priority' => 7002,
        'url' => '',
        'icon' => '&#xe40a;',
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
            //                    [
            //                        'title' => 'Team member Assign',
            //                        'icon' => '',
            //                        'url' => url('/team-member-assign')
            //                    ],
            //                    [
            //                        'title' => 'Item To Group Assign',
            //                        'icon' => '',
            //                        'url' => url('/item-to-group')
            //                    ],

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
            //                    [
            //                        'title' => 'Costing Template',
            //                        'icon' => '',
            //                        'url' => url('/costing-templates')
            //                    ],
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
        'title' => 'System Variables',
        'priority' => 7003,
        'url' => '',
        'icon' => '&#xe8b9;',
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
    [
        'title' => 'PROTRACKER',
        'priority' => 7004,
        'url' => '',
        'icon' => '&#xe14e;',
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
            //                    [
            //                        'title' => 'Sewing Rejection Entry Type',
            //                        'icon' => '',
            //                        'url' => url('/sewing-rejection-entry-type')
            //                    ],
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
        'title' => 'PLANNING',
        'priority' => 7005,
        'url' => '',
        'icon' => '&#xe14e;',
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
        'title' => 'KNITRACKER',
        'priority' => 7006,
        'url' => '',
        'icon' => '&#xe80e;',
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
        'priority' => 7007,
        'url' => '',
        'icon' => '&#xe53e;',
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
            ]
        ],
    ],
    [
        'title' => 'Inventory',
        'priority' => 7008,
        'url' => '',
        'icon' => '&#xe42b;',
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
        'priority' => 7009,
        'url' => '',
        'icon' => '&#xe8ea;',
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
        ],
    ],
    [
        'title' => 'Dyeing',
        'priority' => 7010,
        'url' => '',
        'icon' => '&#xe53e;',
        'items' => [
            [
                'title' => 'Dyeing Company',
                'icon' => '',
                'url' => url('dyeing-company'),
            ],

        ],
    ],
    [
        'title' => 'Sample',
        'priority' => 7010,
        'url' => '',
        'icon' => '&#xe8b5;',
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

];
