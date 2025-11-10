<?php
require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/Cliente.php';

class PedidoRepository extends Repository
{
    public function __construct(string $storagePath = '')
    {
        parent::__construct($storagePath !== '' ? $storagePath : __DIR__ . '/../../storage/pedidos.json');
    }

    protected function toModel(array $item)
    {
        $clienteArr = $item['cliente'] ?? ['nome' => '', 'endereco' => ''];
        $cliente = new Cliente($clienteArr['nome'] ?? '', $clienteArr['endereco'] ?? '');
        return new Pedido((int)($item['id'] ?? 0), $item['id_prato'] ?? [], $cliente);
    }

    public function create(array $data): array
    {
        $entry = [
            'id_prato' => $data['id_prato'] ?? [],
            'cliente' => [
                'nome' => $data['cliente']['nome'] ?? '',
                'endereco' => $data['cliente']['endereco'] ?? ''
            ]
        ];
        return $this->save($entry);
    }

    public function update(int $id, array $data): ?array
    {
        $existing = $this->findById($id);
        if ($existing === null) return null;
        $existing['id_prato'] = $data['id_prato'] ?? $existing['id_prato'] ?? [];
        if (isset($data['cliente'])) {
            $existing['cliente']['nome'] = $data['cliente']['nome'] ?? ($existing['cliente']['nome'] ?? '');
            $existing['cliente']['endereco'] = $data['cliente']['endereco'] ?? ($existing['cliente']['endereco'] ?? '');
        }
        return $this->save($existing);
    }

    public function delete(int $id): bool
    {
        return $this->deleteById($id);
    }
}
?>