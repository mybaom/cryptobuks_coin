<?php

use App\Utils\Workerman\WorkerCallback;

return [
    'client' => [
        'callback_class' => WorkerCallback::class,
        'process_num' => 9,
    ],
];
