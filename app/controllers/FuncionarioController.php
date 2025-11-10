<?php
require_once __DIR__ . '/Controller.php';

class FuncionarioController extends Controller
{
    private function path() { return __DIR__ . '/../../storage/funcionario.json'; }

    public function index()
    {
        $items = $this->readJson($this->path());
        $this->renderView(__DIR__ . '/../../views/gerenciarFuncionario.php', ['items'=>$items]);
    }

    public function create() { $this->renderView(__DIR__ . '/../../views/funcionarios/create.php'); }

    public function store(array $data)
    {
        $items = $this->readJson($this->path());
        $next = 1; foreach ($items as $it) if (!empty($it['id'])) $next = max($next,(int)$it['id']+1);
        $items[] = ['id'=>$next,'nome'=>$data['nome'] ?? '','adm_access'=>!empty($data['adm_access'])?true:false];
        $this->writeJson($this->path(), $items);
        header('Location: /?r=funcionarios/manage'); exit;
    }

    public function edit(int $id)
    {
        $items = $this->readJson($this->path());
        foreach ($items as $it) if ((int)($it['id']??0) === $id) {
            $this->renderView(__DIR__ . '/../../views/funcionarios/edit.php', ['f'=>$it]);
        }
        http_response_code(404); echo "Funcionário não encontrado"; exit;
    }

    public function update(int $id, array $data)
    {
        $items = $this->readJson($this->path());
        foreach ($items as $i=>$it) if ((int)($it['id']??0) === $id) {
            $items[$i]['nome'] = $data['nome'] ?? $it['nome'];
            $items[$i]['adm_access'] = !empty($data['adm_access']);
            $this->writeJson($this->path(), $items);
            header('Location: /?r=funcionarios/manage'); exit;
        }
        http_response_code(404); echo "Funcionário não encontrado"; exit;
    }

    public function delete(int $id)
    {
        $items = $this->readJson($this->path());
        $items = array_values(array_filter($items, fn($it)=> (int)($it['id']??0) !== $id));
        $this->writeJson($this->path(), $items);
        header('Location: /?r=funcionarios/manage'); exit;
    }
}