<?php
require_once __DIR__ . '/../model/Cliente.php';
require_once __DIR__ . '/Controller.php';

class ClienteController extends Controller
{
    private function storagePath(): string
    {
        return __DIR__ . '/../../storage/clientes.json';
    }

    private function readAll(): array
    {
        $raw = @file_get_contents($this->storagePath());
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function writeAll(array $data): void
    {
        file_put_contents($this->storagePath(), json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL, LOCK_EX);
    }

    public function index()
    {
        $clientes = $this->readAll();
        $models = array_map(fn($c) => new Cliente($c['nome'] ?? '', $c['endereco'] ?? ''), $clientes);
        $this->render('clientes/index', ['clientes' => $models]);
    }

    public function show(int $id)
    {
        $all = $this->readAll();
        foreach ($all as $c) {
            if (isset($c['id']) && (int)$c['id'] === $id) {
                $this->render('clientes/show', ['cliente' => new Cliente($c['nome'] ?? '', $c['endereco'] ?? '')]);
                return;
            }
        }
        $this->json(['error' => 'Cliente não encontrado'], 404);
    }

    public function create()
    {
        $this->render('clientes/create');
    }

    public function store(array $data)
    {
        if (empty($data['nome']) || empty($data['endereco'])) {
            $this->json(['error' => 'nome e endereco são obrigatórios'], 400);
        }

        $all = $this->readAll();
        $nextId = 1;
        foreach ($all as $it) {
            if (isset($it['id'])) $nextId = max($nextId, (int)$it['id'] + 1);
        }

        $entry = ['id' => $nextId, 'nome' => $data['nome'], 'endereco' => $data['endereco']];
        $all[] = $entry;
        $this->writeAll($all);

        $this->json($entry, 201);
    }

    public function edit(int $id)
    {
        $all = $this->readAll();
        foreach ($all as $c) {
            if (isset($c['id']) && (int)$c['id'] === $id) {
                $this->render('clientes/edit', ['cliente' => $c]);
                return;
            }
        }
        $this->json(['error' => 'Cliente não encontrado'], 404);
    }

    public function update(int $id, array $data)
    {
        $all = $this->readAll();
        foreach ($all as $i => $c) {
            if (isset($c['id']) && (int)$c['id'] === $id) {
                $all[$i]['nome'] = $data['nome'] ?? $c['nome'];
                $all[$i]['endereco'] = $data['endereco'] ?? $c['endereco'];
                $this->writeAll($all);
                $this->json($all[$i]);
            }
        }
        $this->json(['error' => 'Cliente não encontrado'], 404);
    }

    public function delete(int $id)
    {
        $all = $this->readAll();
        $filtered = array_values(array_filter($all, fn($c) => !isset($c['id']) || (int)$c['id'] !== $id));
        if (count($filtered) === count($all)) {
            $this->json(['deleted' => false], 404);
        }
        $this->writeAll($filtered);
        $this->json(['deleted' => true]);
    }
}
?>