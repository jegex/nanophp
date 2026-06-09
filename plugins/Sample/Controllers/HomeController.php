<?php
namespace NanoPHP\Plugins\Sample\Controllers;

use NanoPHP\Core\Controller;

class HomeController extends Controller {
    public function index(): void {
        $this->render('home', [
            'page_title' => 'Welcome',
        ]);
    }
}
