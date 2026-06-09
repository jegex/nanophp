<?php
namespace NanoPHP\Core;

class App {
    public static function run(): void {
        $debug = Config::get('app.debug', false);

        error_reporting(E_ALL);
        if ($debug) {
            ini_set('display_errors', '1');
        } else {
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
            ini_set('error_log', __DIR__ . '/../error.log');
            set_error_handler([self::class, 'handleError']);
            set_exception_handler([self::class, 'handleException']);
            register_shutdown_function([self::class, 'handleShutdown']);
        }

        self::configureSession();
        session_start();

        self::initLanguage();

        (new PluginManager())->boot();

        $router = new Router();
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        Hook::run('router.before', $uri);
        $router->dispatch($uri);
        Hook::run('router.after', $uri);
    }

    private static function configureSession(): void {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.use_strict_mode', '1');
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            ini_set('session.cookie_secure', '1');
        }
    }

    private static function initLanguage(): void {
        $lang = $_GET['lang'] ?? $_SESSION['lang'] ?? Config::get('app.default_lang', 'en');
        $supported = Config::get('app.supported_langs', []);
        if (!$lang || !array_key_exists($lang, $supported)) {
            $lang = Config::get('app.default_lang', 'en');
        }
        $_SESSION['lang'] = $lang;
        Language::init($lang);
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool {
        if (!(error_reporting() & $severity)) return false;
        if (in_array($severity, [E_DEPRECATED, E_USER_DEPRECATED], true)) return false;
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleException(\Throwable $e): void {
        error_log("NanoPHP Error: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}");
        http_response_code(500);
        if (ob_get_level()) ob_clean();
        try {
            $view = new View();
            $view->render('500', [
                'page_title' => Language::get('500.title'),
                'current_page' => '500',
                'site_name' => Config::get('app.site_name', ''),
                'site_desc' => Config::get('app.site_desc', ''),
                'base_url' => Config::get('app.base_url', ''),
            ]);
        } catch (\Throwable) {
            echo '<h1>500 Server Error</h1>';
        }
        exit;
    }

    public static function handleShutdown(): void {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            error_log("NanoPHP Fatal: {$error['message']} in {$error['file']}:{$error['line']}");
            http_response_code(500);
            if (ob_get_level()) ob_clean();
            echo '<h1>500 Server Error</h1>';
        }
    }
}
