<?php
spl_autoload_register(function (string $class): void {
    $prefix = 'NanoPHP\\';
    $baseDir = __DIR__ . '/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $file = $baseDir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) require $file;
});

spl_autoload_register(function (string $class): void {
    $prefix = 'NanoPHP\\Plugins\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $parts = explode('\\', $relative);
    $pluginName = $parts[0];
    $paths = [
        __DIR__ . "/../plugins/{$pluginName}/" . implode('/', array_slice($parts, 1)) . '.php',
        __DIR__ . "/../plugins/{$pluginName}/" . strtolower(implode('/', array_slice($parts, 1))) . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) { require $path; return; }
    }
});

require __DIR__ . '/helpers.php';
