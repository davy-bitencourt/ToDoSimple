<?php
require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../model/Cliente.php';

class ClienteRepository extends Repository
{
    public function __construct(string $storagePath = '')
    {
        parent::__construct($storagePath !== '' ? $storagePath : __DIR__ . '/../../storage/clientes.json');
    }

    protected function toModel(array $item)
    {
        return new Cliente($item['nome'] ?? '', $item['endereco'] ?? '');
    }

    public function create(array $data): array
    {
        $entry = [
            'nome' => $data['nome'] ?? '',
            'endereco' => $data['endereco'] ?? ''
        ];
        return $this->save($entry);
    }

    public function update(int $id, array $data): ?array
    {
        $existing = $this->findById($id);
        if ($existing === null) return null;
        $existing['nome'] = $data['nome'] ?? $existing['nome'];
        $existing['endereco'] = $data['endereco'] ?? $existing['endereco'];
        return $this->save($existing);
    }

    public function delete(int $id): bool
    {
        return $this->deleteById($id);
    }
}
?>