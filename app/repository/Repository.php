<?php
abstract class Repository
{
    protected string $storagePath;

    /**
     * Construtor: aceita caminho customizado ou usa storage/<ClassName>.json
     */
    public function __construct(string $storagePath = '')
    {
        if ($storagePath !== '') {
            $this->storagePath = $storagePath;
        } else {
            $this->storagePath = __DIR__ . '/../../storage/' . static::class . '.json';
        }

        // garante diretório e arquivo inicializado (seguro para deploy/testes)
        $dir = dirname($this->storagePath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (!file_exists($this->storagePath)) {
            file_put_contents($this->storagePath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL, LOCK_EX);
        }
    }

    /**
     * Leitura e escrita genéricas
     */
    protected function readAll(): array
    {
        $raw = @file_get_contents($this->storagePath);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    protected function writeAll(array $data): void
    {
        file_put_contents($this->storagePath, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL, LOCK_EX);
    }

    /**
     * Operações CRUD genéricas (trabalham com arrays associativos).
     * Subclasses podem expor métodos mais convenientes que convertem modelos <-> array.
     */
    public function getAll(): array
    {
        return $this->readAll();
    }

    public function getAllModels(): array
    {
        return array_map(fn($item) => $this->toModel($item), $this->getAll());
    }

    public function findById(int $id)
    {
        $all = $this->readAll();
        foreach ($all as $item) {
            if (isset($item['id']) && (int)$item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }

    public function findModelById(int $id)
    {
        $item = $this->findById($id);
        return $item === null ? null : $this->toModel($item);
    }

    public function save(array $entry): array
    {
        $all = $this->readAll();

        $nextId = 1;
        foreach ($all as $it) {
            if (isset($it['id'])) {
                $nextId = max($nextId, (int)$it['id'] + 1);
            }
        }

        if (isset($entry['id']) && $entry['id'] !== null) {
            $found = false;
            foreach ($all as $i => $it) {
                if (isset($it['id']) && (int)$it['id'] === (int)$entry['id']) {
                    $all[$i] = $entry;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $all[] = $entry;
            }
        } else {
            $entry['id'] = $nextId;
            $all[] = $entry;
        }

        $this->writeAll($all);
        return $entry;
    }

    public function deleteById(int $id): bool
    {
        $all = $this->readAll();
        $filtered = array_values(array_filter($all, function ($item) use ($id) {
            return !isset($item['id']) || (int)$item['id'] !== $id;
        }));
        $changed = count($filtered) !== count($all);
        if ($changed) {
            $this->writeAll($filtered);
        }
        return $changed;
    }

    /**
     * Subclasses devem sobrescrever toModel para converter array -> model (ex: new User(...))
     */
    abstract protected function toModel(array $item);
}
?>