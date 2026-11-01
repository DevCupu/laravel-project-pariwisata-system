<?php

return [
    'pagination' => [
        'per_page' => env('ADMIN_PAGINATION', 10),
    ],
    'status_badges' => [
        'pending' => 'warning',
        'confirmed' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger',
    ],
];
