<?php
abstract class Controller
{
    protected string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = __DIR__ . '/../../views';
    }

    /**
     * Helpers reutilizáveis para subclasses (render, redirect, json)
     */
    protected function render(string $view, array $data = [], ?string $layout = null): void
    {
        $viewFile = rtrim($this->viewsPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewFile)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return;
        }

        extract($data, EXTR_SKIP);

        if ($layout === null) {
            include $viewFile;
            return;
        }

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        $layoutFile = rtrim($this->viewsPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $layout) . '.php';

        if (file_exists($layoutFile)) {
            include $layoutFile;
            return;
        }

        echo $content;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Métodos CRUD abstratos que subclasses devem implementar conforme suas necessidades.
     * Assinaturas básicas fornecidas; ajuste tipos/retornos conforme cada controller.
     */
    abstract public function index();
    abstract public function show(int $id);
    abstract public function create();
    abstract public function store(array $data);
    abstract public function edit(int $id);
    abstract public function update(int $id, array $data);
    abstract public function delete(int $id);
}
?>