<?php
require_once __DIR__.'/Armazenamento.php';

class Prato {
    public static function listar(): array {
        $pratos = Armazenamento::carregar('pratos');
        usort($pratos, fn($a, $b) => strcmp($a['nome'], $b['nome']));
        return $pratos;
    }

    public static function buscar(int $id): ?array {
        foreach (Armazenamento::carregar('pratos') as $prato) {
            if ($prato['id'] === $id) {
                return $prato;
            }
        }
        return null;
    }

    public static function criar(string $nome, float $preco, string $descricao): void {
        $pratos = Armazenamento::carregar('pratos');
        $pratos[] = [
            'id' => Armazenamento::proximoId($pratos),
            'nome' => $nome,
            'preco' => $preco,
            'descricao' => $descricao,
        ];
        Armazenamento::salvar('pratos', $pratos);
    }

    public static function atualizar(int $id, string $nome, float $preco, string $descricao): void {
        $pratos = Armazenamento::carregar('pratos');
        foreach ($pratos as &$prato) {
            if ($prato['id'] === $id) {
                $prato['nome'] = $nome;
                $prato['preco'] = $preco;
                $prato['descricao'] = $descricao;
            }
        }
        Armazenamento::salvar('pratos', $pratos);
    }

    public static function excluir(int $id): void {
        $pratos = array_values(array_filter(
            Armazenamento::carregar('pratos'),
            fn($prato) => $prato['id'] !== $id
        ));
        Armazenamento::salvar('pratos', $pratos);
    }
}