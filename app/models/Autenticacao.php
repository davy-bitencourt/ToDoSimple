<?php
require_once __DIR__.'/Funcionario.php';

class Autenticacao {
    public static function usuarioAtual(): ?array {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return $_SESSION['usuario'] ?? null;
    }

    public static function autenticar(string $email, string $senha): bool {
        $funcionario = Funcionario::buscarPorEmail($email);
        if ($funcionario && password_verify($senha, $funcionario['senha_hash'])) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['usuario'] = [
                'id' => $funcionario['id'],
                'nome' => $funcionario['nome'],
                'email' => $funcionario['email'],
                'is_admin' => (bool) $funcionario['is_admin'],
            ];
            return true;
        }
        return false;
    }

    public static function exigirUsuario(): void {
        if (!self::usuarioAtual()) {
            header('Location: /?r=func/entrar');
            exit;
        }
    }

    public static function exigirAdministrador(): void {
        self::exigirUsuario();
        $usuario = self::usuarioAtual();
        if (!$usuario['is_admin']) {
            http_response_code(403);
            echo 'Acesso negado';
            exit;
        }
    }

    public static function encerrarSessao(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        $_SESSION = [];
    }
}