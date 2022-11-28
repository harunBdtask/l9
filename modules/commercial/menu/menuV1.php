<?php

return [
    [
        'title' => 'COMMERCIAL',
        'priority' => 5000,
        'url' => '',
        'icon' => '&#xe1bd;',
        'class' => 'nav-header hidden-folded',
        'items' => []
    ],
    [
        'title' => 'Variable',
        'priority' => 5001,
        'icon' => '&#xe2c7;',
        'url' => url('/commercial/commercial-variable/create'),
    ],
    [
        'title' => 'Export Zone',
        'priority' => 5002,
        'url' => '',
        'icon' => '&#xe89e;',
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
        'priority' => 5003,
        'url' => '',
        'icon' => '&#xe89d;',
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
    [
        'title' => 'Reports',
        'priority' => 5004,
        'url' => '',
        'icon' => '&#xe89d;',
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


];
