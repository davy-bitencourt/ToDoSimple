<?php
require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../model/Prato.php';

class PratoRepository extends Repository
{
    public function __construct(string $storagePath = '')
    {
        parent::__construct($storagePath !== '' ? $storagePath : __DIR__ . '/../../storage/pratos.json');
    }

    protected function toModel(array $item)
    {
        return new Prato(
            (int)($item['id'] ?? 0),
            $item['nome'] ?? '',
            $item['id_igredientes'] ?? [],
            $item['id_foto'] ?? ''
        );
    }

    public function create(array $data): array
    {
        $entry = [
            'nome' => $data['nome'] ?? '',
            'id_igredientes' => $data['id_igredientes'] ?? [],
            'id_foto' => $data['id_foto'] ?? ''
        ];
        return $this->save($entry);
    }

    public function update(int $id, array $data): ?array
    {
        $existing = $this->findById($id);
        if ($existing === null) return null;
        $existing['nome'] = $data['nome'] ?? $existing['nome'];
        if (isset($data['id_igredientes'])) $existing['id_igredientes'] = $data['id_igredientes'];
        if (isset($data['id_foto'])) $existing['id_foto'] = $data['id_foto'];
        return $this->save($existing);
    }

    public function delete(int $id): bool
    {
        return $this->deleteById($id);
    }
}
?>