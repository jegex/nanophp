<?php
namespace NanoPHP\Core;

abstract class Plugin {
    private array $pluginConfig = [];

    public function setConfig(array $config): void {
        $this->pluginConfig = $config;
    }

    protected function getConfig(string $key, mixed $default = null): mixed {
        return $this->pluginConfig[$key] ?? $default;
    }

    abstract public function boot(): void;

    abstract public static function getInfo(): array;
}
