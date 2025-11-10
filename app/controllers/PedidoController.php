<?php
require_once __DIR__ . '/Controller.php';

class PedidoController extends Controller
{
    private function storage() { return __DIR__ . '/../../storage/pedidos.json'; }

    public function create()
    {
        $this->renderView(__DIR__ . '/../../views/fazerPredido.php');
    }

    public function store(array $data)
    {
        $clienteNome = trim($data['cliente']['nome'] ?? '');
        $tipo = ($data['tipo'] ?? 'entrega');
        if ($clienteNome === '' || ($tipo === 'entrega' && empty($data['cliente']['endereco']))) {
            http_response_code(400); echo "Dados do cliente incompletos"; exit;
        }
        $all = $this->readJson($this->storage());
        $next = 1; foreach ($all as $it) if (!empty($it['id'])) $next = max($next, (int)$it['id']+1);
        $entry = [
            'id' => $next,
            'tipo' => $tipo,
            'id_prato' => $data['id_prato'] ?? [],
            'cliente' => ['nome'=>$clienteNome,'endereco'=> $data['cliente']['endereco'] ?? ''],
            'status' => 'pendente',
            'created_at' => date('c')
        ];
        $all[] = $entry;
        $this->writeJson($this->storage(), $all);
        header('Location: /?r=pedidos/create&ok=1'); exit;
    }

    public function index()
    {
        $pedidos = $this->readJson($this->storage());
        usort($pedidos, fn($a,$b)=> ($b['id']??0) <=> ($a['id']??0));
        $this->renderView(__DIR__ . '/../../views/gerenciarPedido.php', ['pedidos'=>$pedidos]);
    }

    public function show(int $id)
    {
        $pedidos = $this->readJson($this->storage());
        foreach ($pedidos as $p) if ((int)($p['id']??0) === $id) {
            $this->renderView(__DIR__ . '/../../views/pedidos_show.php', ['pedido'=>$p]);
        }
        http_response_code(404); echo "Pedido não encontrado"; exit;
    }

    public function update(int $id, array $data)
    {
        $all = $this->readJson($this->storage());
        foreach ($all as $i=>$p) {
            if ((int)($p['id']??0) === $id) {
                $status = $data['status'] ?? null;
                if (!in_array($status,['pendente','pronto'], true)) { http_response_code(400); echo "Status inválido"; exit; }
                $all[$i]['status'] = $status;
                $all[$i]['updated_at'] = date('c');
                $this->writeJson($this->storage(), $all);
                header('Location: /?r=pedidos/manage'); exit;
            }
        }
        http_response_code(404); echo "Pedido não encontrado"; exit;
    }

    public function delete(int $id)
    {
        $all = $this->readJson($this->storage());
        $filtered = array_values(array_filter($all, fn($it)=> (int)($it['id']??0) !== $id));
        $this->writeJson($this->storage(), $filtered);
        header('Location: /?r=pedidos/manage'); exit;
    }
}