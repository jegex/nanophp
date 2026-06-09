<?php
namespace NanoPHP\Core;

class Language {
    private static array $lang = [];
    private static ?string $current = null;

    public static function init(string $code): void {
        self::$current = $code;
        $theme = Config::get('app.theme', 'default');
        $paths = [
            __DIR__ . "/../../themes/{$theme}/lang/{$code}.json",
            __DIR__ . "/../../themes/default/lang/{$code}.json",
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                self::$lang = array_replace(self::$lang, json_decode(file_get_contents($path), true) ?: []);
            }
        }
    }

    public static function get(string $key): string {
        $keys = explode('.', $key);
        $value = self::$lang;
        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) $value = $value[$k];
            else return $key;
        }
        return is_string($value) ? $value : $key;
    }

    public static function current(): string {
        return self::$current ?? Config::get('app.default_lang', 'en');
    }
}

function __(string $key): string {
    return Language::get($key);
}
