<?php

return [
    'secret' => env('NO_CAPTCHA_SECRET'),
    'sitekey' => env('NO_CAPTCHA_SITE'),
    'options' => [
        'timeout' => 30,
    ],
];
