<?php

return [
    'fileName' => '',
    'seconds' => 0,
    'lines' => 300,

    'email' => [
        'emails' => [
            'example@mail.com',
            'another@example.org',
        ],
    ],

    // https://discord.com/developers/docs/resources/webhook#execute-webhook
    'discord' => [
        'webhook' => [
            'id' => 'WEBHOOK_ID',
            'token' => 'WEBHOOK_TOKEN',
        ],
        'message' => [
            'content' => '@everyone',
            'allowed_mentions' => [
                'parse' => ['everyone']
            ],
            'username' => env('APP_NAME', 'Discord Notifier'),
            'components' => [
                [
                    'type' => 2,
                    'style' => 5,
                    'label' => 'Home Page',
                    'url' => env('APP_URL'),
                ]
            ],
        ],
    ],
];
