<?php

return [
    'fileName' => storage_path('logs/laravel.log'),
    'seconds' => 3600,
    'lines' => 300,

//    'email' => [
//        'emails' => [
//            'example@mail.com',
//            'another@example.org',
//        ],
//        'subject' => env('APP_NAME', 'Discord Notifier') . ' - laravel.log',
//    ],

    // https://discord.com/developers/docs/resources/webhook#execute-webhook
//    'discord' => [
//        'webhook' => [
//            'id' => env('DISCORD_NOTIFIER_WEBHOOK_ID'),
//            'token' => env('DISCORD_NOTIFIER_WEBHOOK_TOKEN'),
//        ],
//        'message' => [
//            'content' => '<@326629196967313408>',
//            'allowed_mentions' => [
//                'parse' => ['users'],
//            ],
//            'username' => env('APP_NAME', 'Discord Notifier'),
//            'avatar_url' => 'https://avatars.dicebear.com/api/bottts/'. urlencode(env('APP_NAME', 'Discord Notifier')) .'.png',
//        ],
//    ],
];
