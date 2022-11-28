<?php


// sample menu

return [
    [
        'title' => 'Time & Action',
        'priority' => 1005,
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

    ],
];
