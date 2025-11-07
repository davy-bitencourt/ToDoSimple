<?php
class Pedido{
    public function __construct(
        private int $id,
        private array $id_prato = [],
        private Cliente $cliente,
    ){}
}
?>