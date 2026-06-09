<?php
namespace NanoPHP\Core;

abstract class Controller {
    protected View $view;

    public function __construct() {
        $this->view = new View();
        $this->initLanguage();
    }

    protected function data(): DataSourceInterface {
        return DataSourceManager::get();
    }

    private function initLanguage(): void {
        $lang = $_GET['lang'] ?? $_SESSION['lang'] ?? Config::get('app.default_lang', 'en');
        if (!array_key_exists($lang, Config::get('app.supported_langs', []))) {
            $lang = Config::get('app.default_lang', 'en');
        }
        $_SESSION['lang'] = $lang;
        Language::init($lang);
    }

    protected function render(string $view, array $data = []): void {
        $viewData = array_merge([
            'page_title' => Config::get('app.site_name'),
            'site_name' => Config::get('app.site_name'),
            'site_desc' => Config::get('app.site_desc'),
            'current_page' => $this->view->currentPage(),
            'base_url' => base_url(),
        ], $data);
        $this->view->render($view, $viewData);
    }

    protected function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function notFound(): void {
        http_response_code(404);
        $this->render('404', ['page_title' => 'Page Not Found']);
        exit;
    }
}
