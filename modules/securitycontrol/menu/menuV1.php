<?php

return [
    [
        'title' => 'SECURITY CONTROL',
        'priority' => 7520,
        'url' => '',
        'icon' => '',
        'class' => 'nav-header hidden-folded',
        'items' => [],
    ],
    [
        'title' => 'Security Control',
        'priority' => 7521,
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
                    ],
                ],
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
                    ],

                ],
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
                    ],
                ],
            ],

        ],
    ],
];
