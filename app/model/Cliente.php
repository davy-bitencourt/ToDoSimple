<?php
class Cliente{
    public string $nome;
    public string $endereco;

    public function __construct(
        string $nome,
        string $endereco,
    ) {
        $this->nome = $nome;
        $this->endereco = $endereco;
    }
}
?>