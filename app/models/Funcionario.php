<?php
require_once __DIR__.'/Armazenamento.php';

class Funcionario {
    public static function listar(): array {
        $funcionarios = Armazenamento::carregar('funcionarios');
        usort($funcionarios, fn($a, $b) => strcmp($a['nome'], $b['nome']));
        return array_map(
            fn($funcionario) => [
                'id' => $funcionario['id'],
                'nome' => $funcionario['nome'],
                'email' => $funcionario['email'],
                'is_admin' => $funcionario['is_admin'],
            ],
            $funcionarios
        );
    }

    public static function criar(string $nome, string $email, string $senha, bool $administrador): void {
        $funcionarios = Armazenamento::carregar('funcionarios');
        $funcionarios[] = [
            'id' => Armazenamento::proximoId($funcionarios),
            'nome' => $nome,
            'email' => $email,
            'senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
            'is_admin' => $administrador ? 1 : 0,
        ];
        Armazenamento::salvar('funcionarios', $funcionarios);
    }

    public static function excluir(int $id): void {
        $funcionarios = array_values(array_filter(
            Armazenamento::carregar('funcionarios'),
            fn($funcionario) => $funcionario['id'] !== $id
        ));
        Armazenamento::salvar('funcionarios', $funcionarios);
    }

    public static function buscarPorEmail(string $email): ?array {
        foreach (Armazenamento::carregar('funcionarios') as $funcionario) {
            if ($funcionario['email'] === $email) {
                return $funcionario;
            }
        }
        return null;
    }
}