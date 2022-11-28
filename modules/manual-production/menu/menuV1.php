<?php

return [
    [
        'title' => 'PRODUCTION',
        'priority' => 2002,
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
            [
                'title' => 'Reports',
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
            ]
        ]
    ]
];
