<?php
class Funcionario{
    public function __construct(
        private int $id,
        private string $nome,
        private bool $adm_access,
    ){}
}
?>