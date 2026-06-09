<?php
namespace NanoPHP\Core;

class Hook {
    private static array $actions = [];

    public static function add(string $hook, callable $callback, int $priority = 10, ?string $id = null): string {
        $id ??= spl_object_hash($callback);
        self::$actions[$hook][$priority][$id] = $callback;
        ksort(self::$actions[$hook]);
        return $id;
    }

    public static function remove(string $hook, string $id): void {
        foreach (self::$actions[$hook] ?? [] as $priority => &$callbacks) {
            unset($callbacks[$id]);
        }
    }

    public static function removeAll(string $hook, ?int $priority = null): void {
        if ($priority === null) {
            unset(self::$actions[$hook]);
        } else {
            unset(self::$actions[$hook][$priority]);
        }
    }

    public static function has(string $hook): bool {
        return !empty(self::$actions[$hook]);
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
