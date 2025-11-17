<?php
require_once __DIR__.'/Armazenamento.php';

class Funcionario {
    private const STORAGE_KEY = 'funcionarios';

    private static function carregar(): array {
        return Armazenamento::carregar(self::STORAGE_KEY);
    }

    public static function buscarPorEmail(string $email): ?array {
        foreach (self::carregar() as $funcionario) {
            if ($funcionario['email'] === $email) {
                return $funcionario;
            }
        }
        return null;
    }
}
