<?php
namespace NanoPHP\Core;

class Config {
    private static array $cache = [];

    public static function load(string $group): array {
        if (!isset(self::$cache[$group])) {
            $path = __DIR__ . '/../../config/' . $group . '.php';
            self::$cache[$group] = file_exists($path) ? require $path : [];
        }
        return self::$cache[$group];
    }

    public static function get(string $key, mixed $default = null): mixed {
        $parts = explode('.', $key);
        $group = array_shift($parts);
        $data = self::load($group);
        foreach ($parts as $part) {
            if (!is_array($data) || !isset($data[$part])) return $default;
            $data = $data[$part];
        }
        return $data;
    }
}
