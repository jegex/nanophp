<?php
namespace NanoPHP\Core;

class View {
    private string $theme;
    private array $data = [];
    private array $sections = [];
    private ?string $layout = null;
    private string $viewsDir;

    public function __construct(?string $theme = null) {
        $this->theme = $theme ?? Config::get('app.theme', 'default');
        $this->viewsDir = __DIR__ . "/../../themes/{$this->theme}/views/";
    }

    public function render(string $view, array $data = []): void {
        $data = Hook::filter('view.render', $data, $view);
        $this->data = $data;

        $viewPath = $this->findView($view);
        if (!$viewPath) {
            http_response_code(500);
            echo "View not found: {$view}";
            return;
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutPath = $this->findView($this->layout);
            if ($layoutPath) {
                ob_start();
                require $layoutPath;
                $content = ob_get_clean();
            }
        }

        echo $content;
    }

    public function section(string $name): void {
        if (isset($this->sections[$name])) {
            echo $this->sections[$name];
        }
    }

    public function extend(string $layout): void {
        $this->layout = $layout;
    }

    public function start(string $name): void {
        ob_start();
        $this->sections[$name] = '';
    }

    public function end(string $name): void {
        $this->sections[$name] = ob_get_clean();
    }

    public function partial(string $name, array $data = []): void {
        $this->data = array_merge($this->data, $data);
        $path = $this->findView('partials/' . $name);
        if ($path) require $path;
    }

    public function __get(string $name): mixed {
        return $this->data[$name] ?? null;
    }

    public function __(string $key): string {
        return Language::get($key);
    }

    public function asset(string $path): string {
        $base = Config::get('app.base_url', '');
        return "{$base}/themes/{$this->theme}/assets/{$path}";
    }

    public function currentPage(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        return trim($uri, '/') ?: 'index';
    }

    private function findView(string $name): ?string {
        $paths = [
            __DIR__ . "/../../themes/{$this->theme}/views/{$name}.php",
            __DIR__ . "/../../themes/default/views/{$name}.php",
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) return $path;
        }
        return null;
    }
}
