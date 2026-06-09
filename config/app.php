<?php
return [
    'site_name' => 'NanoPHP',                                // Used in <title> and branding
    'site_desc' => 'Lightweight PHP MVC Framework',          // Default meta description
    'debug' => true,                                         // true = display errors, false = log to app/error.log
    'theme' => 'default',                                    // Theme folder name (themes/{name}/)
    'default_lang' => 'en',                                  // Fallback language code
    'supported_langs' => ['en' => 'English', 'id' => 'Bahasa Indonesia'], // Available languages
    'lang_prefix' => false,                                  // true = /en/page /id/page, false = ?lang=id
    'hideDefaultLocaleInURL' => true,                        // false = /en/page tetap tampil
    'useAcceptLanguageHeader' => false,                      // auto-detect browser language
    'localesOrder' => [],                                    // ['id', 'en'] — custom order
    'localesMapping' => [],                                  // ['en' => 'english', 'id' => 'indonesia']
    'utf8suffix' => 'UTF-8',                                // locale suffix for setlocale() (CentOS etc)
    'urlsIgnored' => [],                                     // ['/api', '/sitemap.xml']
    'base_url' => '',                                        // Empty = auto-detect, or set https://example.com
    'contact_email' => 'contact@example.com',                // Contact form recipient
];
