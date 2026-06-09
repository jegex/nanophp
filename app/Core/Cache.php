<?php
namespace NanoPHP\Core;

class Cache {
    private static ?string $dir = null;

    public static function init(?string $dir = null): void {
        self::$dir = $dir ?? __DIR__ . '/../../cache/';
        if (!is_dir(self::$dir)) {
            mkdir(self::$dir, 0755, true);
        }
    }

    public static function get(string $key, mixed $default = null): mixed {
        $path = self::path($key);
        if (!file_exists($path)) return $default;
        $data = file_get_contents($path);
        if ($data === false) return $default;
        $entry = json_decode($data, true);
        if (!isset($entry['expires'], $entry['value'])) return $default;
        if (time() >= $entry['expires']) {
            unlink($path);
            return $default;
        }
        return $entry['value'];
    }

    public static function set(string $key, mixed $value, int $ttl = 3600): void {
        if (!self::$dir) self::init();
        $entry = json_encode([
            'value' => $value,
            'expires' => time() + $ttl,
        ]);
        file_put_contents(self::path($key), $entry, LOCK_EX);
    }

    public static function delete(string $key): void {
        $path = self::path($key);
        if (file_exists($path)) unlink($path);
    }

    public static function flush(): void {
        if (!self::$dir) return;
        foreach (glob(self::dir() . '*') as $f) {
            if (basename($f) !== '.gitkeep') unlink($f);
        }
    }

    public static function remember(string $key, int $ttl, callable $callback): mixed {
        $cached = self::get($key);
        if ($cached !== null) return $cached;
        $value = $callback();
        self::set($key, $value, $ttl);
        return $value;
    }

    private static function path(string $key): string {
        return self::dir() . md5($key) . '.cache';
    }

    private static function dir(): string {
        if (!self::$dir) self::init();
        return rtrim(self::$dir, '/') . '/';
    }
}
