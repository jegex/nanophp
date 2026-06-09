<?php
namespace NanoPHP\Core;

class Hook {
    private static array $actions = [];
    private static array $filters = [];

    public static function add(string $hook, callable $callback, int $priority = 10): void {
        self::$actions[$hook][$priority][] = $callback;
        ksort(self::$actions[$hook]);
    }

    public static function remove(string $hook, callable $callback): void {
        if (!isset(self::$actions[$hook])) return;
        foreach (self::$actions[$hook] as $priority => &$callbacks) {
            $callbacks = array_filter($callbacks, fn($c) => $c !== $callback);
        }
    }

    public static function run(string $hook, mixed ...$args): void {
        if (!isset(self::$actions[$hook])) return;
        foreach (self::$actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $callback(...$args);
            }
        }
    }

    public static function filter(string $hook, mixed $value, mixed ...$args): mixed {
        if (!isset(self::$actions[$hook])) return $value;
        foreach (self::$actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $value = $callback($value, ...$args);
            }
        }
        return $value;
    }
}
