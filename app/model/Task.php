<?php
namespace app\model;

class Task {
    private string $file;

    public function __construct(string $storageFile) {
        $this->file = $storageFile;
        $dir = dirname($this->file);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        if (!is_file($this->file)) file_put_contents($this->file, json_encode([]));
    }

    private function readAll(): array {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        foreach ($data as &$t) {
            $t["descricao"] ??= "";
            $t["feito"] ??= false;
        }
        return $data;
    }

    private function writeAll(array $items): void {
        file_put_contents($this->file, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function all(): array {
        $tasks = $this->readAll();
        usort($tasks, fn($a, $b) => $a["id"] <=> $b["id"]);
        return $tasks;
    }

    public function find(int $id): ?array {
        foreach ($this->readAll() as $t)
            if ($t["id"] === $id) return $t;
        return null;
    }

    public function create(string $titulo, string $descricao = ""): void {
        $tasks = $this->readAll();
        $id = empty($tasks) ? 1 : max(array_column($tasks, "id")) + 1;
        $tasks[] = [
            "id" => $id,
            "titulo" => trim($titulo),
            "descricao" => trim($descricao),
            "feito" => false,
            "criado_em" => date("c")
        ];
        $this->writeAll($tasks);
    }

    public function update(int $id, string $titulo, string $descricao, bool $feito): bool {
        $tasks = $this->readAll();
        foreach ($tasks as &$t) {
            if ($t["id"] === $id) {
                $t["titulo"] = trim($titulo);
                $t["descricao"] = trim($descricao);
                $t["feito"] = $feito;
                $this->writeAll($tasks);
                return true;
            }
        }
        return false;
    }

    public function toggle(int $id): bool {
        $tasks = $this->readAll();
        foreach ($tasks as &$t) {
            if ($t["id"] === $id) {
                $t["feito"] = !$t["feito"];
                $this->writeAll($tasks);
                return true;
            }
        }
        return false;
    }

    public function delete(int $id): bool {
        $tasks = array_filter($this->readAll(), fn($t) => $t["id"] !== $id);
        $this->writeAll(array_values($tasks));
        return true;
    }
}
