<?php
require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../model/Igredientes.php'; // note: class name in model file is `igredientes`

class IgredientesRepository extends Repository
{
    public function __construct(string $storagePath = '')
    {
        parent::__construct($storagePath !== '' ? $storagePath : __DIR__ . '/../../storage/igredientes.json');
    }

    protected function toModel(array $item)
    {
        return new igredientes((int)($item['id'] ?? 0), $item['nome'] ?? '');
    }

    public function create(array $data): array
    {
        $entry = ['nome' => $data['nome'] ?? ''];
        return $this->save($entry);
    }

    public function update(int $id, array $data): ?array
    {
        $existing = $this->findById($id);
        if ($existing === null) return null;
        $existing['nome'] = $data['nome'] ?? $existing['nome'];
        return $this->save($existing);
    }

    public function delete(int $id): bool
    {
        return $this->deleteById($id);
    }
}
?>