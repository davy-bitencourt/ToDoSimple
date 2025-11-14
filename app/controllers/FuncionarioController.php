<?php
require_once __DIR__.'/../models/Autenticacao.php';
require_once __DIR__.'/../models/Funcionario.php';

class FuncionarioController {
    public static function entrar() {
        $title = 'Entrar';
        ob_start();
        include __DIR__.'/../views/funcionarios/login.php';
        $content = ob_get_clean();
        include __DIR__.'/../views/layout/main.php';
    }

    public static function autenticar() {
        if (Autenticacao::autenticar($_POST['email'], $_POST['senha'])) {
            $_SESSION['flash'] = 'Bem-vindo!';
            header('Location: /?r=pedido/listar');
        } else {
            $_SESSION['flash'] = 'Login inválido.';
            header('Location: /?r=func/entrar');
        }
    }

    public static function sair() {
        Autenticacao::exigirUsuario();
        Autenticacao::encerrarSessao();
        header('Location: /');
    }

    public static function gerenciar() {
        Autenticacao::exigirAdministrador();
        $funcionarios = Funcionario::listar();
        $title = 'Funcionários';
        ob_start();
        include __DIR__.'/../views/funcionarios/gerenciar.php';
        $content = ob_get_clean();
        include __DIR__.'/../views/layout/main.php';
    }

    public static function criar() {
        Autenticacao::exigirAdministrador();
        Funcionario::criar($_POST['nome'], $_POST['email'], $_POST['senha'], (bool) $_POST['is_admin']);
        $_SESSION['flash'] = 'Funcionário criado.';
        header('Location: /?r=func/gerenciar');
    }

    public static function excluir() {
        Autenticacao::exigirAdministrador();
        Funcionario::excluir((int) $_POST['id']);
        $_SESSION['flash'] = 'Funcionário excluído.';
        header('Location: /?r=func/gerenciar');
    }
}