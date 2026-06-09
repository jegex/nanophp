<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Identity
    |--------------------------------------------------------------------------
    |
    | Used in <title> tags and navigation branding throughout the application.
    |
    */

    'site_name' => 'NanoPHP',
    'site_desc' => 'Lightweight PHP MVC Framework',

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | true  → display_errors on, error_reporting(E_ALL). Use during development.
    | false → logs errors to app/error.log, shows a friendly 500 page to users.
    |
    */

    'debug' => true,

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | Name of the active theme folder. Theme must exist in themes/{name}/ with
    | a theme.json file and a views/ directory.
    |
    */

    'theme' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    |
    | default_lang              Fallback when user has no session, URL prefix,
    |                           or browser match.
    |
    | supported_langs           Available languages as [code => label].
    |                           Label appears in language switcher UI.
    |
    | lang_prefix               URL mode: false → ?lang=id (query param),
    |                           true → /id/page (URL prefix).
    |
    | hide_default_locale_in_url  Only applies when lang_prefix=true.
    |                           true  → default lang has no prefix (/page),
    |                                    others use prefix (/id/page).
    |                           false → all langs have prefix (/en/page),
    |                                    no prefix falls back to session/default.
    |
    | use_accept_language_header Auto-detect browser language on first visit.
    |                           Parses Accept-Language header with q-value
    |                           prioritization.
    |
    | locales_order             Custom sort order for language switcher UI.
    |                           Empty = follows supported_langs order.
    |
    | locales_mapping           Rename URL segments for each language.
    |                           Example: ['en' => 'english', 'id' => 'indonesia']
    |                           Result:  /english/page, /indonesia/page
    |
    | utf8_suffix               Locale string suffix for setlocale(LC_TIME).
    |                           Default 'UTF-8'. CentOS may need 'utf8'.
    |                           Set empty string '' to disable setlocale().
    |
    | urls_ignored              Path prefixes that skip language detection.
    |                           Example: ['/api', '/sitemap.xml', '/webhook']
    |
    */

    'default_lang' => 'en',
    'supported_langs' => ['en' => 'English', 'id' => 'Bahasa Indonesia'],
    'lang_prefix' => false,
    'hide_default_locale_in_url' => true,
    'use_accept_language_header' => true,
    'locales_order' => [],
    'locales_mapping' => [],
    'utf8_suffix' => 'UTF-8',
    'urls_ignored' => [],

    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    |
    | base_url  Empty = auto-detect from $_SERVER. Set explicitly for CLI usage
    |           or subfolder deployments.
    |           Examples: '', 'https://example.com', 'https://example.com/blog'
    |
    */

    'base_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    |
    | Recipient email for the built-in contact form.
    |
    */

    'contact_email' => 'contact@example.com',

];
