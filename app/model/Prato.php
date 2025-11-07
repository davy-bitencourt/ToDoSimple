<?php
class Prato{
    public function __construct(
        private int $id,
        private string $nome,
        private array $id_igredientes = [],
        private string $id_foto,
    ){}
}
?>