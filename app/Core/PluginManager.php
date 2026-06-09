<?php
namespace NanoPHP\Core;

class PluginManager {
    private array $plugins = [];

    public function boot(): void {
        $this->registerAutoload();

        $plugins = $this->discover();

        foreach ($plugins as $name => $cfg) {
            if (!$cfg['enabled']) continue;

            $class = "NanoPHP\\Plugins\\{$name}\\{$name}";
            if (!class_exists($class)) continue;

            $instance = new $class();
            if (!$instance instanceof Plugin) continue;

            if (!empty($cfg['config'])) {
                $instance->setConfig($cfg['config']);
            }

            $instance->boot();
            $this->plugins[] = $instance;
        }

        Hook::run('app.boot');
    }

    private function discover(): array {
        $pluginsDir = __DIR__ . '/../../plugins/';
        $active = Config::get('plugins.active', []);
        $allConfigs = Config::get('plugins.config', []);
        $plugins = [];

        $dirs = glob($pluginsDir . '*', GLOB_ONLYDIR);
        if ($dirs) {
            foreach ($dirs as $dir) {
                $name = basename($dir);
                if (!file_exists($dir . '/plugin.json')) continue;
                $plugins[$name] = [
                    'enabled' => in_array($name, $active),
                    'config' => $allConfigs[$name] ?? [],
                ];
            }
        }

        return $plugins;
    }

    private function registerAutoload(): void {
        spl_autoload_register(function (string $class): void {
            $prefix = 'NanoPHP\\Plugins\\';
            if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;

            $relative = substr($class, strlen($prefix));
            $parts = explode('\\', $relative);
            $pluginName = $parts[0];
            $fileName = implode('/', array_slice($parts, 1));

            $paths = [
                __DIR__ . "/../../plugins/{$pluginName}/{$fileName}.php",
                __DIR__ . "/../../plugins/{$pluginName}/{$fileName}/{$fileName}.php",
                __DIR__ . "/../../plugins/{$pluginName}/" . strtolower($fileName) . '.php',
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) { require $path; return; }
            }
        });
    }
}
