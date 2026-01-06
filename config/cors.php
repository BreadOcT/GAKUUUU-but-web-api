<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'], // Izinkan semua method (POST, GET, PUT, dll)

    'allowed_origins' => ['*'], // <--- PENTING: Izinkan semua asal request

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Izinkan semua header

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // Set false jika pakai '*' di allowed_origins
];
