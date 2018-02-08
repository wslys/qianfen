<?php
return [
    'IM' => [
        'onStart' => true,
        'trace' => true,
        'worker' => [
            'businesswork',
            'gateway',
            'register',
            // 'web',
            'remote',
        ]
    ],
    'FileMonitor' => [
        'onStart' => true,
        'trace' => true,
        'worker' => [
            'start'
        ]
    ]
];