<?php
namespace NanoPHP\Plugins\Sample;

use NanoPHP\Core\Hook;
use NanoPHP\Core\Plugin;

class Sample extends Plugin {
    public static function getInfo(): array {
        return [
            'name' => 'Sample',
            'description' => 'Sample plugin with a home route',
            'version' => '1.0.0',
        ];
    }

    public function boot(): void {
        $routes = require __DIR__ . '/config/routes.php';
        Hook::add('router.routes', fn(array $existing): array => array_merge($existing, $routes));
    }
}
