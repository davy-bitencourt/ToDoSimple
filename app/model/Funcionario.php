<?php
class Funcionario{
    private int $id;
    private string $nome;
    private bool $adm_access;

    public function __construct(
        int $id, 
        string $nome, 
        bool $adm_access
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->adm_access = $adm_access;
    }
}
?>