<?php
return [
    'default' => 'json',                                     // Active data source key
    'sources' => [
        'json' => [
            'type' => 'json',                                // Data source type
            'path' => '',                                    // Path to JSON files (empty = plugin-defined)
            'cache_ttl' => 0,                                // Cache seconds (0 = disabled)
        ],
        'api' => [
            'type' => 'api',                                 // Data source type
            'base_url' => '',                                // API endpoint base URL
            'api_key' => '',                                 // API authentication key
            'cache_ttl' => 0,                                // Cache seconds (0 = disabled)
        ],
    ],
];
