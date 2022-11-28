<?php

return [
    ['title' => 'NEW ACCOUNTING & FINANCE',
        'priority' => 20000,
        'url' => '',
        'icon' => '&#xebd;',
        'class' => 'nav-header hidden-folded',
        'items' => []
    ],
    [
        'title' => 'Chart of Accounts',
        'url' => url('finance/accounts'),
        'priority' => 20001,
        'icon' => '&#xe24b;',
    ],
    [
        'title' => 'Library',
        'priority' => 20002,
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
                            // [
                            //     'title' => 'Cheque Books',
                            //     'icon' => '',
                            //     'url' => url('/basic-finance/cheque-books'),
                            // ],
                            // [
                            //     'title' => 'Receive Bank List',
                            //     'icon' => '',
                            //     'url' => url('/basic-finance/receive-banks'),
                            // ],
                            // [
                            //     'title' => 'Receive Cheque List',
                            //     'icon' => '',
                            //     'url' => url('/basic-finance/receive-cheques'),
                            // ],
                        ],
                    ]
                ]
            ]
        ]
    ],

    [
        'title' => 'Voucher',
        'priority' => 20003,
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
                    // [
                    //     'title' => 'Debit Voucher',
                    //     'icon' => '',
                    //     'url' => url('finance/vouchers/create?voucher_type=debit'),
                    // ],
                    // [
                    //     'title' => 'Credit Voucher',
                    //     'icon' => '',
                    //     'url' => url('finance/vouchers/create?voucher_type=credit'),
                    // ],
                    // [
                    //     'title' => 'Journal Voucher',
                    //     'icon' => '',
                    //     'url' => url('finance/vouchers/create?voucher_type=journal'),
                    // ],
                    // [
                    //     'title' => 'Contra Voucher',
                    //     'icon' => '',
                    //     'url' => url('finance/vouchers/create?voucher_type=contra'),
                    // ],
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
        'priority' => 20004,
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
//            [
//                'title' => 'Un Cheque Clear List',
//                'icon' => '',
//                'url' => url('finance/unclear-cheque-list'),
//            ],
        ],
    ],
    [
        'title' => 'Supplier Management',
        'priority' => 20005,
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
        'priority' => 20006,
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

    [
        'title' => 'Reports',
        'priority' => 20007,
        'url' => '',
        'icon' => '&#xe880;',
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
    ];
