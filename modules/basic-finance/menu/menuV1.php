<?php

return [
    ['title' => 'ACCOUNTING & FINANCE',
     'priority' => 6000,
     'url' => '',
     'icon' => '&#xebd;',
     'class' => 'nav-header hidden-folded',
     'items' => []
    ],
    //    [
    //        'title' => 'Chart of Accounts',
    //        'url' => url('basic-finance/accounts'),
    //        'priority' => 6001,
    //        'icon' => '&#xe24b;',
    //    ],
    //    [
    //        'title' => 'Debit Voucher',
    //        'priority' => 6002,
    //        'url' => url('basic-finance/vouchers/create?voucher_type=debit'),
    //        'icon' => '&#xe8a1;',
    //    ],
    //    [
    //        'title' => 'Credit Voucher',
    //        'priority' => 6003,
    //        'url' => url('basic-finance/vouchers/create?voucher_type=credit'),
    //        'icon' => '&#xe234;',
    //    ],
    //    [
    //        'title' => 'Journal Voucher',
    //        'priority' => 6004,
    //        'url' => url('basic-finance/vouchers/create?voucher_type=journal'),
    //        'icon' => '&#xe236;',
    //    ],
    //    [
    //        'title' => 'Contra Voucher',
    //        'priority' => 6005,
    //        'url' => url('basic-finance/vouchers/create?voucher_type=contra'),
    //        'icon' => '&#xe237;',
    //    ],
    //    [
    //        'title' => 'Reports',
    //        'priority' => 6007,
    //        'url' => '',
    //        'icon' => '&#xe880;',
    //        'items' => [
    //            [
    //                'title' => 'Voucher List',
    //                'url' => url('basic-finance/vouchers'),
    //                'icon' => '',
    //            ],
    //            [
    //                'title' => 'Transactions',
    //                'url' => url('basic-finance/transactions'),
    //                'icon' => '',
    //            ],
    //            [
    //                'title' => 'Ledger',
    //                'url' => url('basic-finance/ledger'),
    //                'icon' => '',
    //            ],
    //            [
    //                'title' => 'Group Ledger',
    //                'url' => url('basic-finance/group-ledger'),
    //                'icon' => '',
    //            ],
    //            [
    //                'title' => 'Trial Balance',
    //                'url' => url('basic-finance/trial-balance'),
    //                'icon' => '',
    //            ],
    //            [
    //                'title' => 'Income Statement',
    //                'url' => url('basic-finance/income-statement'),
    //                'icon' => '',
    //            ],
    //            [
    //                'title' => 'Balance Sheet',
    //                'url' => url('basic-finance/balance-sheet'),
    //                'icon' => '',
    //            ],
    //            [
    //                'title' => 'Receipt Payment Report',
    //                'url' => url('basic-finance/receipts-and-payments'),
    //                'icon' => ''
    //            ],
    //            [
    //                'title' => 'Month Wise Receipt Payment Report',
    //                'url' => url('basic-finance/month-wise-receipt-payment-report'),
    //                'icon' => ''
    //            ]
    //        ]
    //    ],
    //    [
    //        'title' => 'Settings',
    //        'priority' => 6008,
    //        'url' => '',
    //        'icon' => '&#xe8b8;',
    //        'items' => [
    //            [
    //                'title' => 'Companies',
    //                'icon' => '',
    //                'url' => url('basic-finance/companies'),
    //            ]
    //        ]
    //    ],
    //new menu list
    [
        'title' => 'Library',
        'priority' => 6001,
        'url' => '',
        'icon' => '',
        'items' => [
            [
                'title' => 'Entry',
                'icon' => '',
                'url' => '',
                'items' => [
//                    [
//                        'title' => 'Company Create',
//                        'icon' => '',
//                        'url' => url('basic-finance/companies'),
//                    ],
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
        'priority' => 6002,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Cash Management',
        'priority' => 6003,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Bank Management',
        'priority' => 6004,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Vendor Management',
        'priority' => 6005,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Purchase Management',
        'priority' => 6006,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Customers Management',
        'priority' => 6007,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Sales Management',
        'priority' => 6008,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Assets Manager',
        'priority' => 6009,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Inventories Management',
        'priority' => 6010,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Cost & Budget Management',
        'priority' => 6011,
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
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'System Setting',
        'priority' => 6012,
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
    [
        'title' => 'Financial Reporting',
        'priority' => 6013,
        'url' => '',
        'icon' => '',
        'items' => [
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Ratio Analysis',
        'priority' => 6014,
        'url' => '',
        'icon' => '',
        'items' => [
            [
                'title' => 'Reports',
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
//                    [
//                        'title' => 'ICRRS',
//                        'icon' => '',
//                        'url' => url('basic-finance/maintenance'),
//                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'Requisition & Reports',
        'priority' => 6015,
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
            [
                'title' => 'Ac Reports',
                'url' => '',
                'icon' => '&#xe880;',
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
                    //                    [
                    //                        'title' => 'Trial Balance V2',
                    //                        'url' => url('basic-finance/trial-balance-v2'),
                    //                        'icon' => '',
                    //                    ],
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
        ],
    ],
    [
        'title' => 'Procurement',
        'priority' => 6016,
        'url' => '',
        'icon' => '',
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
];
