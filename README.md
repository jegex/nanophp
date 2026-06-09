# NanoPHP — Mini MVC PHP Framework

A lightweight, zero-dependency PHP MVC framework with plugin system, theme engine, and multi-language support.

No Composer. No external PHP libraries. Upload and run.

## Features

- **Mini MVC** — Front controller, pattern-based router, controller layer, view engine
- **Plugin System** — Hook-driven architecture (`app.boot`, `router.*`, `controller.*`, `view.render`)
- **Theme Engine** — Layout inheritance, sections, partials, asset management, multi-language
- **Data Source Abstraction** — Generic `getAll()` / `get()` interface for any backend
- **File Cache** — JSON-based, integrated into data sources, configurable TTL
- **PSR-4 Autoloading** — Custom autoloader, no Composer required
- **PHP 8.1+** — Native, no extensions beyond standard PHP
- **Clean URLs** — Apache `.htaccess` and Nginx-ready

## Requirements

- PHP 8.1+
- Apache with `mod_rewrite` or Nginx (see config below)
- Node.js (optional — CSS compilation only)

## Quick Start

```bash
php -S localhost:8000 router.php
```

Open `http://localhost:8000`. Append `?lang=id` for Indonesian.

```bash
npm install && npm run build   # optional — compile Tailwind CSS
```

## Structure

```
nanophp/
├── index.php              # Front controller
├── router.php             # Dev server with static file support
├── .htaccess              # Apache URL rewriting
├── app/                   # Framework source (PSR-4: NanoPHP\)
│   ├── autoload.php       # PSR-4 autoloader
│   ├── helpers.php        # Global helper functions
│   ├── Core/              # Framework engine
│   │   ├── App.php        # Bootstrap and run
│   │   ├── Router.php     # Pattern-to-regex route dispatch
│   │   ├── Controller.php # Base controller
│   │   ├── View.php       # Layout/section/partial rendering
│   │   ├── Config.php     # Configuration loader
│   │   ├── Language.php   # Translation engine
│   │   ├── Hook.php       # Event/filter system for plugins
│   │   ├── Plugin.php     # Abstract base class for plugins
│   │   ├── PluginManager.php # Plugin boot + autoloader
│   │   ├── Cache.php      # File-based caching layer
│   │   ├── DataSourceInterface.php
│   │   └── DataSourceManager.php
│   └── error.log          # Error log (production mode)
├── config/                # Configuration files
│   ├── app.php            # Site name, theme, language
│   ├── data.php           # Data source setting
│   ├── offers.php         # CPA offer definitions
│   └── plugins.php        # Active plugins + config
├── themes/
│   └── default/           # Default theme
│       ├── theme.json     # Theme metadata
│       ├── input.css      # Tailwind CSS source
│       ├── views/         # Page templates
│       │   ├── layout.php
│       │   ├── home.php
│       │   ├── 404.php
│       │   └── 500.php
│       ├── partials/      # Navigation, footer
│       ├── lang/          # Translations (en.json, id.json)
│       └── assets/        # Compiled CSS, JS, images
├── cache/                 # File cache storage
├── plugins/
│   └── Sample/            # Sample plugin (home route)
│       ├── sample.php     # Boot class
│       ├── plugin.json    # Metadata
│       ├── Controllers/   # HomeController
│       └── config/        # Routes
└── package.json           # Tailwind CSS build scripts
```

## Creating a Plugin

Each domain lives in a plugin under `plugins/{Name}/`. Activate in `config/plugins.php`.

```
plugins/MyPlugin/
├── myplugin.php       # Plugin class (lowercase file)
├── plugin.json        # Metadata
├── Controllers/       # Your controllers
└── config/            # routes.php etc.
```

**Plugin class:**
```php
namespace NanoPHP\Plugins\MyPlugin;

use NanoPHP\Core\Hook;
use NanoPHP\Core\Plugin;

class MyPlugin extends Plugin {
    public static function getInfo(): array {
        return ['name' => 'My Plugin', 'version' => '1.0.0', 'description' => 'What it does'];
    }

    public function boot(): void {
        $routes = require __DIR__ . '/config/routes.php';
        Hook::add('router.routes', fn(array $existing): array => array_merge($existing, $routes));
    }
}
```

**Routes** (`plugins/MyPlugin/config/routes.php`):
```php
return [
    '/' => ['NanoPHP\\Plugins\\MyPlugin\\Controllers\\HomeController', 'index'],
];
```

**Controller:**
```php
namespace NanoPHP\Plugins\MyPlugin\Controllers;

use NanoPHP\Core\Controller;

class HomeController extends Controller {
    public function index(): void {
        $this->render('home', ['page_title' => 'Welcome']);
    }
}
```

### Routing

Route patterns support `{param}` placeholders. Routes from all active plugins merge via the `router.routes` hook.

```php
'/'              => ['NanoPHP\\Plugins\\MyPlugin\\Controllers\\HomeController', 'index'],
'/page/{slug}'   => ['NanoPHP\\Plugins\\MyPlugin\\Controllers\\PageController', 'show'],
```

### View

```php
<?php $this->extend('layout') ?>
<?php $this->section('content') ?>
  <h1><?= $page_title ?></h1>
<?php $this->endSection() ?>
```

Available: `extend()`, `section()`/`endSection()`, `partial()`, `asset()`.

### Hooks

| Hook | Arguments | Description |
|------|-----------|-------------|
| `app.boot` | — | After all plugins loaded |
| `router.before` | `$uri` | Before route matching |
| `router.after` | `$uri` | After route matched |
| `router.routes` | `array` | Filter — merge plugin routes |
| `controller.before` | `$instance, $method, $params` | Before controller method |
| `controller.after` | `$instance, $method, $params, $result` | After controller method |
| `view.render` | `$data, $view` | Filter — modify view data |

### Helpers

```php
config('app.site_name');        // Config::get() shortcut
url('page');                     // URL with base_url prefix
e($user_input);                  // HTML escape
str_limit($text, 50);            // truncate
array_get($data, 'key.nested');  // deep array access
dd($var);                        // dump and die
```

### Cache

```php
Cache::remember('key', $ttl, fn() => expensiveCall());
```

Enable per source in `config/data.php` (`cache_ttl > 0`). JSON format (no object injection).

## Production

```bash
# Set debug false
# Enable cache
npm run build   # production CSS
```

## CSS

```bash
npm run build    # Compile Tailwind CSS v4
npm run watch    # Watch for changes
npm run serve    # PHP dev server
```

## License

MIT
