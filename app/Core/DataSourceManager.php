<?php
namespace NanoPHP\Core;

class DataSourceManager {
    private static ?DataSourceInterface $source = null;

    public static function set(DataSourceInterface $source): void {
        self::$source = $source;
    }

    public static function get(): DataSourceInterface {
        if (self::$source === null) {
            self::$source = new class implements DataSourceInterface {
                public function getAll(string $resource, array $params = []): array { return []; }
                public function get(string $resource, string $id): ?array { return null; }
            };
        }
        return self::$source;
    }
}
