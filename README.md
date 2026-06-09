# NanoPHP — Mini MVC PHP Framework

[![GitHub](https://img.shields.io/badge/GitHub-jegex/nanophp-181717?style=flat&logo=github)](https://github.com/jegex/nanophp)

A lightweight, zero-dependency PHP MVC framework with plugin system, theme engine, and multi-language support.

No Composer. No external PHP libraries. Upload and run.

## Features

- **Mini MVC** — Front controller, pattern-based router, controller layer, view engine
- **Plugin System** — Auto-discovered from `plugins/*/plugin.json`, hook-driven architecture
- **Theme Engine** — Layout inheritance, sections, partials, asset management, multi-language
- **Data Source Abstraction** — Generic `getAll()` / `get()` interface for any backend
- **Form Validation** — Built-in validator with 10 rules (required, email, min, max, matches, etc.)
- **Hook System** — Priority-based event system with ID registration and removal
- **File Cache** — JSON-based, integrated into data sources, configurable TTL
- **PSR-4 Autoloading** — Custom autoloader, no Composer required
- **PHP 8.1+** — Native, no extensions beyond standard PHP
- **Clean URLs** — Apache `.htaccess` and Nginx-ready

## Requirements

- PHP 8.1+
- Apache with `mod_rewrite` or Nginx

## Installation

```bash
git clone https://github.com/jegex/nanophp.git
cd nanophp
php -S localhost:8000 router.php
```

Open `http://localhost:8000`. Append `?lang=id` for Indonesian, or enable prefix mode (`lang_prefix: true`) for `/id/page` URLs.

Edit `config/app.php` to set your site name, base URL, and language. For production, upload the folder to any PHP 8.1+ host — no Composer needed.

> **Note:** `router.php` is for the **PHP dev server only**. It serves static files directly and passes all other requests to `index.php`. In production, Apache/Nginx handle static files natively — just ensure URL rewriting points to `index.php`.

### Quick Start

```bash
php -S localhost:8000 router.php
```

## Creating a Plugin

Each domain lives in a plugin under `plugins/{Name}/`. Plugins are auto-discovered from `plugin.json`. Enable them in `config/plugins.php`.

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

Data passed to `render()` is accessible via `$this->` inside templates (no `extract()` — prevents variable collision):

```php
<?php $this->extend('layout') ?>
<?php $this->start('content') ?>
  <h1><?= $this->page_title ?></h1>
<?php $this->end('content') ?>
```

Common view variables (always available from `Controller::render()`):

| Variable | Source |
|----------|--------|
| `$this->page_title` | page-specific override or `site_name` |
| `$this->site_name` | `config('app.site_name')` |
| `$this->site_desc` | `config('app.site_desc')` |
| `$this->base_url` | `base_url()` helper — includes lang prefix if enabled |
| `$this->current_page` | current URI path, e.g. `index`, `about` |

Methods: `extend()`, `start()`/`end()`, `partial()`, `asset()`, `section()` (echo a section defined in child view).

**Translations** in templates:

```php
<?= $this->__('nav.home') ?>     <!-- via View -->
<?= __('nav.home') ?>            <!-- via global helper -->
```

**Raw injection points** (set via hook or controller data):

```php
<?= $this->head_append ?? '' ?>   <!-- before </head> -->
<?= $this->body_append ?? '' ?>   <!-- before </body> -->
```

### Language

Two modes controlled by `lang_prefix` in `config/app.php`:

**Query mode** (`lang_prefix: false`, default):
```
?lang=id    → Indonesian
?lang=en    → English
```

**Prefix mode** (`lang_prefix: true`):
```
/id/page    → Indonesian
/en/page    → English
/page       → default language (if hide_default_locale_in_url: true)
```

When `hide_default_locale_in_url` is `true` (default), the default language has no prefix —
`/page` loads the default locale. Set to `false` to always show the prefix: `/en/page`.

**Auto-detection:** When `use_accept_language_header` is `true`, first-time visitors without a session or URL parameter get matched against their browser's `Accept-Language` header.

**URL segment aliasing** with `locales_mapping`:
```php
'locales_mapping' => ['en' => 'english', 'id' => 'indonesia']
```
Result: `/indonesia/tentang`, `/english/about`.

**Translation files** live in `themes/{theme}/lang/{code}.json`:

```json
{
  "nav": {
    "home": "Home",
    "about": "About"
  },
  "404": {
    "title": "Page Not Found",
    "message": "The page you are looking for does not exist.",
    "back_home": "Back to Home"
  }
}
```

Retrieve translations in controllers or views:

```php
Language::get('nav.home');   // via class
__('nav.home');               // via global helper
$this->__('nav.home');        // via View
```

See `config/app.php` for all language configuration options.

### Hooks

Plugins register callbacks via `Hook::add()` and can provide an optional ID for removal:

```php
Hook::add('router.routes', $callback, $priority = 10, $id = null);
Hook::remove('router.routes', $id);
Hook::removeAll('router.routes', $priority = null);
Hook::has('router.routes'); // bool
```

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
base_url('/page');               // Full URL with lang prefix if enabled
url('/page', ['q' => 'foo']);    // base_url() + query string
e($user_input);                  // HTML escape
__('nav.home');                  // Language::get() shortcut
str_limit($text, 50);            // Truncate with ellipsis
array_get($data, 'key.nested');  // Deep array access
dd($var);                        // Dump and die
```

`base_url()` auto-appends the language prefix in prefix mode. `url()` builds on `base_url()` and accepts an optional query array.

### Cache

```php
Cache::remember('key', $ttl, fn() => expensiveCall());
```

Set TTL in your data source implementation. JSON format (no object injection).

### Validation

Built-in form validation via `NanoPHP\Core\Validator`:

```php
use NanoPHP\Core\Validator;

$v = new Validator($_POST, [
    'name'  => 'required|min:3|max:255',
    'email' => 'required|email',
    'age'   => 'numeric|min:18',
    'url'   => 'url',
    'password' => 'required|min:8',
    'confirm'  => 'required|matches:password',
]);

if ($v->fails()) {
    $errors = $v->errors();   // ['field' => 'message', ...]
    $first  = $v->error('email'); // first error for a field
} else {
    $data = $v->getData();    // sanitized validated data
}
```

Supported rules: `required`, `email`, `url`, `alpha`, `alphanumeric`, `numeric`, `min:N`, `max:N`, `matches:field`, `regex:/pattern/`.

Custom field aliases for error messages:

```php
$v->setAlias('email', 'Email Address');
```

## Production

```bash
# Set debug: false in config/app.php — errors log to app/error.log
# Point Apache/Nginx document root to the project folder
# Apache: mod_rewrite handles clean URLs via .htaccess
# Nginx:  try_files $uri $uri/ /index.php;
```

No `composer install` or file generation needed. Just upload and run.

## License

MIT &copy; 2026
