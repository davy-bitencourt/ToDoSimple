<?php
class Prato{
    private int $id;
    private string $nome;
    private array $id_igredientes = [];
    private string $id_foto;

    public function __construct(
        int $id, 
        string $nome, 
        array $id_igredientes = [], 
        string $id_foto
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->id_igredientes = $id_igredientes;
        $this->id_foto = $id_foto;
    }
}
?>