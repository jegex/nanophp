<?php
return [
    'site_name' => 'NanoPHP',                                // Used in <title> and branding
    'site_desc' => 'Lightweight PHP MVC Framework',          // Default meta description
    'debug' => true,                                         // true = display errors, false = log to app/error.log
    'theme' => 'default',                                    // Theme folder name (themes/{name}/)
    'default_lang' => 'en',                                  // Fallback language code
    'supported_langs' => ['en' => 'English', 'id' => 'Bahasa Indonesia'], // Available languages
    'base_url' => '',                                        // Empty = auto-detect, or set https://example.com
    'contact_email' => 'contact@example.com',                // Contact form recipient
];
