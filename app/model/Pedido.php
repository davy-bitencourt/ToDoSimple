<?php
class Pedido{
    private int $id;
    private array $id_prato = [];
    private Cliente $cliente;

    public function __construct(
        int $id, 
        array $id_prato = [], 
        Cliente $cliente
    ) {
        $this->id = $id;
        $this->id_prato = $id_prato;
        $this->cliente = $cliente;
    }
}
?>