<?php
class igredientes{
    private int $id;
    private string $nome;

    public function __construct(
        int $id, 
        string $nome
    ) {
        $this->id = $id;
        $this->nome = $nome;
    }
}
?>