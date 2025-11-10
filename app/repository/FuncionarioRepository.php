<?php
require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../model/Funcionario.php';

class FuncionarioRepository extends Repository
{
    public function __construct(string $storagePath = '')
    {
        parent::__construct($storagePath !== '' ? $storagePath : __DIR__ . '/../../storage/funcionario.json');
    }

    protected function toModel(array $item)
    {
        return new Funcionario(
            (int)($item['id'] ?? 0),
            $item['nome'] ?? '',
            (bool)($item['adm_access'] ?? false)
        );
    }

    public function create(array $data): array
    {
        $entry = [
            'nome' => $data['nome'] ?? '',
            'adm_access' => isset($data['adm_access']) ? (bool)$data['adm_access'] : false
        ];
        return $this->save($entry);
    }

    public function update(int $id, array $data): ?array
    {
        $existing = $this->findById($id);
        if ($existing === null) return null;
        $existing['nome'] = $data['nome'] ?? $existing['nome'];
        if (isset($data['adm_access'])) $existing['adm_access'] = (bool)$data['adm_access'];
        return $this->save($existing);
    }

    public function delete(int $id): bool
    {
        return $this->deleteById($id);
    }
}
?>