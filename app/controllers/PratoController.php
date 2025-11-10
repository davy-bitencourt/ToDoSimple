<?php
require_once __DIR__ . '/../model/Prato.php';
require_once __DIR__ . '/Controller.php';

class PratoController extends Controller
{
    private function storagePath(): string
    {
        return __DIR__ . '/../../storage/pratos.json';
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
        $this->render('pratos/index', ['pratos' => $this->readAll()]);
    }

    public function show(int $id)
    {
        foreach ($this->readAll() as $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $this->render('pratos/show', ['prato' => $p]);
                return;
            }
        }
        $this->json(['error' => 'Prato não encontrado'], 404);
    }

    public function create()
    {
        $this->render('pratos/create');
    }

    public function store(array $data)
    {
        if (empty($data['nome'])) $this->json(['error' => 'nome obrigatório'], 400);
        $all = $this->readAll();
        $nextId = 1;
        foreach ($all as $it) if (isset($it['id'])) $nextId = max($nextId, (int)$it['id'] + 1);
        $entry = [
            'id' => $nextId,
            'nome' => $data['nome'],
            'id_igredientes' => $data['id_igredientes'] ?? [],
            'id_foto' => $data['id_foto'] ?? ''
        ];
        $all[] = $entry;
        $this->writeAll($all);
        $this->json($entry, 201);
    }

    public function edit(int $id)
    {
        foreach ($this->readAll() as $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $this->render('pratos/edit', ['prato' => $p]);
                return;
            }
        }
        $this->json(['error' => 'Prato não encontrado'], 404);
    }

    public function update(int $id, array $data)
    {
        $all = $this->readAll();
        foreach ($all as $i => $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $all[$i]['nome'] = $data['nome'] ?? $p['nome'];
                if (isset($data['id_igredientes'])) $all[$i]['id_igredientes'] = $data['id_igredientes'];
                if (isset($data['id_foto'])) $all[$i]['id_foto'] = $data['id_foto'];
                $this->writeAll($all);
                $this->json($all[$i]);
            }
        }
        $this->json(['error' => 'Prato não encontrado'], 404);
    }

    public function delete(int $id)
    {
        $all = $this->readAll();
        $filtered = array_values(array_filter($all, fn($it) => !isset($it['id']) || (int)$it['id'] !== $id));
        if (count($filtered) === count($all)) {
            $this->json(['deleted' => false], 404);
        }
        $this->writeAll($filtered);
        $this->json(['deleted' => true]);
    }
}
?>