<?php

function config(?string $key = null, mixed $default = null): mixed {
    if ($key === null) return \NanoPHP\Core\Config::load('app');
    return \NanoPHP\Core\Config::get($key, $default);
}

function base_url(string $path = ''): string {
    $base = \NanoPHP\Core\Config::get('app.base_url', '');
    if (\NanoPHP\Core\Config::get('app.lang_prefix', false)) {
        $default = \NanoPHP\Core\Config::get('app.default_lang', 'en');
        $hideDefault = \NanoPHP\Core\Config::get('app.hideDefaultLocaleInURL', true);
        $current = \NanoPHP\Core\Language::current();
        if (!$hideDefault || $current !== $default) {
            $mapping = \NanoPHP\Core\Config::get('app.localesMapping', []);
            $base .= '/' . ($mapping[$current] ?? $current);
        }
    }
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function url(string $path = '', array $query = []): string {
    $url = base_url($path);
    if (!empty($query)) {
        $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
    }
    return $url;
}

function dd(mixed ...$vars): void {
    echo '<pre style="background:#0a0e17;color:#f1f5f9;padding:1rem;font:14px/1.5 monospace;border-radius:8px;max-height:90vh;overflow:auto;">';
    foreach ($vars as $i => $var) {
        echo "<strong style='color:#22c55e'>#" . ($i + 1) . "</strong>\n";
        echo htmlspecialchars(print_r($var, true), ENT_QUOTES, 'UTF-8');
        echo "\n\n";
    }
    echo '</pre>';
    exit;
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function str_limit(?string $text, int $limit = 100, string $end = '...'): string {
    $text = trim($text ?? '');
    if (mb_strlen($text) <= $limit) return $text;
    return mb_substr($text, 0, $limit) . $end;
}

function array_get(array $array, string $key, mixed $default = null): mixed {
    $keys = explode('.', $key);
    foreach ($keys as $segment) {
        if (!is_array($array) || !array_key_exists($segment, $array)) return $default;
        $array = $array[$segment];
    }
    return $array;
}
