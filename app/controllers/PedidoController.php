<?php
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/Cliente.php';
require_once __DIR__ . '/Controller.php';

class PedidoController extends Controller
{
    private function storagePath(): string
    {
        return __DIR__ . '/../../storage/pedidos.json';
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
        $all = $this->readAll();
        $models = array_map(fn($p) => new Pedido(
            (int)($p['id'] ?? 0),
            $p['id_prato'] ?? [],
            new Cliente($p['cliente']['nome'] ?? '', $p['cliente']['endereco'] ?? '')
        ), $all);
        $this->render('pedidos/index', ['pedidos' => $models]);
    }

    public function show(int $id)
    {
        $all = $this->readAll();
        foreach ($all as $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $model = new Pedido(
                    (int)$p['id'],
                    $p['id_prato'] ?? [],
                    new Cliente($p['cliente']['nome'] ?? '', $p['cliente']['endereco'] ?? '')
                );
                $this->render('pedidos/show', ['pedido' => $model]);
                return;
            }
        }
        $this->json(['error' => 'Pedido não encontrado'], 404);
    }

    public function create()
    {
        $this->render('pedidos/create');
    }

    public function store(array $data)
    {
        // espera: id_prato (array) e cliente (array com nome,endereco)
        if (empty($data['cliente']['nome']) || empty($data['cliente']['endereco'])) {
            $this->json(['error' => 'cliente.nome e cliente.endereco são obrigatórios'], 400);
        }

        $all = $this->readAll();
        $nextId = 1;
        foreach ($all as $it) {
            if (isset($it['id'])) $nextId = max($nextId, (int)$it['id'] + 1);
        }

        $entry = [
            'id' => $nextId,
            'id_prato' => $data['id_prato'] ?? [],
            'cliente' => [
                'nome' => $data['cliente']['nome'],
                'endereco' => $data['cliente']['endereco']
            ]
        ];

        $all[] = $entry;
        $this->writeAll($all);

        $this->json($entry, 201);
    }

    public function edit(int $id)
    {
        $all = $this->readAll();
        foreach ($all as $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $this->render('pedidos/edit', ['pedido' => $p]);
                return;
            }
        }
        $this->json(['error' => 'Pedido não encontrado'], 404);
    }

    public function update(int $id, array $data)
    {
        $all = $this->readAll();
        foreach ($all as $i => $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $all[$i]['id_prato'] = $data['id_prato'] ?? ($p['id_prato'] ?? []);
                if (isset($data['cliente'])) {
                    $all[$i]['cliente']['nome'] = $data['cliente']['nome'] ?? ($p['cliente']['nome'] ?? '');
                    $all[$i]['cliente']['endereco'] = $data['cliente']['endereco'] ?? ($p['cliente']['endereco'] ?? '');
                }
                $this->writeAll($all);
                $this->json($all[$i]);
                return;
            }
        }
        $this->json(['error' => 'Pedido não encontrado'], 404);
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