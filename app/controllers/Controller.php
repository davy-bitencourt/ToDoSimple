<?php
class Controller
{
    protected function renderView(string $viewPath, array $vars = []): void
    {
        extract($vars, EXTR_SKIP);
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout.php';
        exit;
    }

    protected function readJson(string $path): array
    {
        if (!is_file($path)) return [];
        $d = json_decode(file_get_contents($path), true);
        return is_array($d) ? $d : [];
    }

    protected function writeJson(string $path, array $data): void
    {
        file_put_contents($path, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL, LOCK_EX);
    }
}