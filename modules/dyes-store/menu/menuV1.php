<?php


return [
    [
        'title' => 'Dyes & Chemicals Store',
        'priority' => 4005,
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
];
