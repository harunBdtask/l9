<?php

return [
    [
        'title' => 'PLANING',
        'priority' => 6500,
        'url' => '',
        'icon' => '&#xe1bd;',
        'class' => 'nav-header hidden-folded',
        'items' => [],
    ],
    [
        'title' => 'Capacity Plan',
        'priority' => 6501,
        'url' => '',
        'icon' => '&#xe8e1;',
        'items' => [
            [
                'title' => 'Capacity Planning Entry',
                'url' => url('/planning/capacity-planning-entry'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Capacity Availability',
                'url' => url('/planning/capacity-availability'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Reports',
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
        ],
    ],
    [
        'title' => 'Container Plan',
        'priority' => 6502,
        'url' => '',
        'icon' => '&#xe3e8;',
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
        ],
    ],
    [
        'title' => 'Sewing Plan',
        'priority' => 6503,
        'url' => '',
        'icon' => '&#xe3e8;',
        'items' => [
            [
                'title' => 'Sewing Line Capacity',
                'url' => url('/line-capacity-entry'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Sewing Capacity Inquiry',
                'url' => url('/order-wise-capacity-inquiry'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Sewing Plan Board',
                'url' => url('/sewing-plan'),
                'icon' => '',
                'items' => [],
            ],
            [
                'title' => 'Reports',
                'url' => '',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Sewing Plan Report',
                        'url' => url('/sewing-line-plan-report'),
                        'icon' => '',
                        'items' => [],
                    ],
                ],
            ],
        ],
    ],
];
