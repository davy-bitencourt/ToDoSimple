<?php
namespace app\controllers;

use app\model\Task;

class TaskController {
    private Task $model;
    private string $layout;

    public function __construct() {
        $root = dirname(__DIR__, 2);
        $this->model  = new Task($root . "/storage/data.json");
        $this->layout = $root . "/app/view/layout.php";
    }

    private function render(string $view, array $data = []): void {
        require $this->layout;
    }

    private function redirect(string $to = "/"): void {
        header("Location: {$to}");
        exit;
    }

    public function index(): void {
        $tasks = $this->model->all();
        $this->render("home", ["tasks" => $tasks]);
    }

    public function store(array $post): void {
        $titulo = trim($post["titulo"] ?? "");
        $descricao = trim($post["descricao"] ?? "");
        if ($titulo === "") { $this->redirect("/"); }
        $this->model->create($titulo, $descricao);
        $this->redirect("/");
    }

    public function edit(int $id): void {
        $tarefa = $this->model->find($id);
        if (!$tarefa) { $this->redirect("/"); }
        $this->render("edit", ["tarefa" => $tarefa]);
    }

    public function update(int $id, array $post): void {
        $titulo = trim($post["titulo"] ?? "");
        $descricao = trim($post["descricao"] ?? "");
        $feito  = isset($post["feito"]);
        if ($titulo === "") { $this->redirect("/"); }
        $this->model->update($id, $titulo, $descricao, $feito);
        $this->redirect("/");
    }

    public function toggle(int $id): void {
        $this->model->toggle($id);
        $this->redirect("/");
    }

    public function destroy(int $id): void {
        $this->model->delete($id);
        $this->redirect("/");
    }
}
