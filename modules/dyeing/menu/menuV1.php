<?php

return [
    [
        'title' => 'Textile Dyeing(In-House)',
        'priority' => 2102,
        'url' => '',
        'icon' => '&#xe1bd;',
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
            [
                'title' => 'Reports',
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
];
