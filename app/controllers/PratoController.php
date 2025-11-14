<?php
require_once __DIR__.'/../models/Prato.php';
require_once __DIR__.'/../models/Autenticacao.php';

class PratoController {
    public static function paginaInicial() {
        $pratos = Prato::listar();
        $title = 'Cardápio';
        ob_start();
        include __DIR__.'/../views/pratos/home.php';
        $content = ob_get_clean();
        include __DIR__.'/../views/layout/main.php';
    }

    public static function listar() {
        Autenticacao::exigirUsuario();
        $pratos = Prato::listar();
        $title = 'Pratos';
        ob_start();
        include __DIR__.'/../views/pratos/index.php';
        $content = ob_get_clean();
        include __DIR__.'/../views/layout/main.php';
    }

    public static function novo() {
        Autenticacao::exigirAdministrador();
        $prato = [];
        $isEdit = false;
        $title = 'Novo prato';
        ob_start();
        include __DIR__.'/../views/pratos/form.php';
        $content = ob_get_clean();
        include __DIR__.'/../views/layout/main.php';
    }

    public static function criar() {
        Autenticacao::exigirAdministrador();
        Prato::criar($_POST['nome'], (float) $_POST['preco'], $_POST['descricao'] ?? '');
        $_SESSION['flash'] = 'Prato criado.';
        header('Location: /?r=prato/listar');
    }

    public static function editar() {
        Autenticacao::exigirAdministrador();
        $prato = Prato::buscar((int) ($_GET['id'] ?? 0));
        $isEdit = true;
        $title = 'Editar prato';
        ob_start();
        include __DIR__.'/../views/pratos/form.php';
        $content = ob_get_clean();
        include __DIR__.'/../views/layout/main.php';
    }

    public static function atualizar() {
        Autenticacao::exigirAdministrador();
        Prato::atualizar((int) $_POST['id'], $_POST['nome'], (float) $_POST['preco'], $_POST['descricao'] ?? '');
        $_SESSION['flash'] = 'Prato atualizado.';
        header('Location: /?r=prato/listar');
    }

    public static function excluir() {
        Autenticacao::exigirAdministrador();
        Prato::excluir((int) $_POST['id']);
        $_SESSION['flash'] = 'Prato excluído.';
        header('Location: /?r=prato/listar');
    }
}