<?php

return [
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', 'mailto:your-email@example.com'),
        'publicKey' => env('VAPID_PUBLIC_KEY'),
        'privateKey' => env('VAPID_PRIVATE_KEY'),
    ],
];