<?php
require_once __DIR__ . '/Controller.php';

class PratoController extends Controller
{
    private function pratosPath() { return __DIR__ . '/../../storage/pratos.json'; }
    private function ingsPath() { return __DIR__ . '/../../storage/igredientes.json'; }

    public function manage()
    {
        $pratos = $this->readJson($this->pratosPath());
        $ings = $this->readJson($this->ingsPath());
        $this->renderView(__DIR__ . '/../../views/gerenciarPratos.php', ['pratos'=>$pratos,'ings'=>$ings]);
    }

    public function create() { $this->renderView(__DIR__ . '/../../views/pratos_create.php', ['ings'=>$this->readJson($this->ingsPath())]); }
    public function store(array $data)
    {
        $pratos = $this->readJson($this->pratosPath());
        $next = 1; foreach ($pratos as $p) if (!empty($p['id'])) $next = max($next,(int)$p['id']+1);
        $pratos[] = ['id'=>$next,'nome'=>$data['nome'] ?? '','id_igredientes'=>$data['id_igredientes'] ?? []];
        $this->writeJson($this->pratosPath(), $pratos);
        header('Location: /?r=pratos/manage'); exit;
    }

    public function edit(int $id)
    {
        $pratos = $this->readJson($this->pratosPath());
        foreach ($pratos as $p) if ((int)($p['id']??0) === $id) {
            $this->renderView(__DIR__ . '/../../views/pratos_edit.php', ['prato'=>$p,'ings'=>$this->readJson($this->ingsPath())]);
        }
        http_response_code(404); echo "Prato não encontrado"; exit;
    }

    public function update(int $id, array $data)
    {
        $pratos = $this->readJson($this->pratosPath());
        foreach ($pratos as $i=>$p) if ((int)($p['id']??0) === $id) {
            $pratos[$i]['nome'] = $data['nome'] ?? $p['nome'];
            $pratos[$i]['id_igredientes'] = $data['id_igredientes'] ?? [];
            $this->writeJson($this->pratosPath(), $pratos);
            header('Location: /?r=pratos/manage'); exit;
        }
        http_response_code(404); echo "Prato não encontrado"; exit;
    }

    public function delete(int $id)
    {
        $pratos = $this->readJson($this->pratosPath());
        $pratos = array_values(array_filter($pratos, fn($it)=> (int)($it['id']??0) !== $id));
        $this->writeJson($this->pratosPath(), $pratos);
        header('Location: /?r=pratos/manage'); exit;
    }
}