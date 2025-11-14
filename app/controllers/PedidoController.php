<?php
require_once __DIR__.'/../models/Prato.php';
require_once __DIR__.'/../models/Pedido.php';
require_once __DIR__.'/../models/Autenticacao.php';
require_once __DIR__.'/../models/Carrinho.php';

class PedidoController {
    public static function novo() {
        if (isset($_GET['prato_id'])) {
            $prato = Prato::buscar((int) $_GET['prato_id']);
            if ($prato) {
                Carrinho::adicionar($prato['id'], 1);
                $_SESSION['flash'] = 'Prato adicionado ao carrinho.';
            } else {
                $_SESSION['flash'] = 'Prato inexistente.';
            }
        }
        header('Location: /?r=carrinho');
    }

    public static function criar() {
        $clienteNome = trim($_POST['cliente_nome'] ?? '');
        $tipo = $_POST['tipo_entrega'] ?? '';
        $clienteEndereco = trim($_POST['cliente_endereco'] ?? '');
        $pratoId = (int) ($_POST['prato_id'] ?? 0);

        if ($clienteNome === '' || !in_array($tipo, ['retirada', 'entrega'], true)) {
            $_SESSION['flash'] = 'Dados inválidos para o pedido.';
            header('Location: /');
            return;
        }

        $prato = Prato::buscar($pratoId);
        if (!$prato) {
            $_SESSION['flash'] = 'Prato inexistente.';
            header('Location: /');
            return;
        }

        Pedido::criar([
            'cliente_nome' => $clienteNome,
            'cliente_endereco' => $tipo === 'entrega' ? $clienteEndereco : null,
            'tipo_entrega' => $tipo,
            'itens' => [
                [
                    'prato_id' => $prato['id'],
                    'quantidade' => 1,
                ],
            ],
        ]);
        $_SESSION['flash'] = 'Pedido criado!';
        header('Location: /');
    }

    public static function listar() {
        Autenticacao::exigirUsuario();
        $pedidos = Pedido::listar();
        $title = 'Pedidos';
        ob_start();
        include __DIR__.'/../views/pedidos/lista.php';
        $content = ob_get_clean();
        include __DIR__.'/../views/layout/main.php';
    }

    public static function atualizarStatus() {
        Autenticacao::exigirUsuario();
        Pedido::definirStatus((int) $_POST['id'], $_POST['status']);
        $_SESSION['flash'] = 'Status atualizado.';
        header('Location: /?r=pedido/listar');
    }
}
