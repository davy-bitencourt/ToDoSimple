<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__.'/../app/controllers/PratoController.php';
require_once __DIR__.'/../app/controllers/PedidoController.php';
require_once __DIR__.'/../app/controllers/FuncionarioController.php';
require_once __DIR__.'/../app/controllers/CarrinhoController.php';

$r = $_GET['r'] ?? 'inicio';
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

switch ($r) {
    case 'inicio':
        PratoController::paginaInicial();
        break;

    case 'prato/listar':
        PratoController::listar();
        break;
    case 'prato/novo':
        PratoController::novo();
        break;
    case 'prato/criar':
        if ($metodo === 'POST') {
            PratoController::criar();
        } else {
            http_response_code(405);
        }
        break;
    case 'prato/editar':
        PratoController::editar();
        break;
    case 'prato/atualizar':
        if ($metodo === 'POST') {
            PratoController::atualizar();
        } else {
            http_response_code(405);
        }
        break;
    case 'prato/excluir':
        if ($metodo === 'POST') {
            PratoController::excluir();
        } else {
            http_response_code(405);
        }
        break;

    case 'pedido/novo':
        PedidoController::novo();
        break;
    case 'pedido/criar':
        if ($metodo === 'POST') {
            PedidoController::criar();
        } else {
            http_response_code(405);
        }
        break;
    case 'pedido/listar':
        PedidoController::listar();
        break;
    case 'pedido/atualizar-status':
        if ($metodo === 'POST') {
            PedidoController::atualizarStatus();
        } else {
            http_response_code(405);
        }
        break;

    case 'func/entrar':
        FuncionarioController::entrar();
        break;
    case 'func/autenticar':
        if ($metodo === 'POST') {
            FuncionarioController::autenticar();
        } else {
            http_response_code(405);
        }
        break;
    case 'func/sair':
        FuncionarioController::sair();
        break;
    case 'func/gerenciar':
        FuncionarioController::gerenciar();
        break;
    case 'func/criar':
        if ($metodo === 'POST') {
            FuncionarioController::criar();
        } else {
            http_response_code(405);
        }
        break;
    case 'func/excluir':
        if ($metodo === 'POST') {
            FuncionarioController::excluir();
        } else {
            http_response_code(405);
        }
        break;

    case 'carrinho':
        CarrinhoController::ver();
        break;
    case 'carrinho/adicionar':
        if ($metodo === 'POST') {
            CarrinhoController::adicionar();
        } else {
            http_response_code(405);
        }
        break;
    case 'carrinho/atualizar':
        if ($metodo === 'POST') {
            CarrinhoController::atualizar();
        } else {
            http_response_code(405);
        }
        break;
    case 'carrinho/remover':
        if ($metodo === 'POST') {
            CarrinhoController::remover();
        } else {
            http_response_code(405);
        }
        break;
    case 'carrinho/limpar':
        if ($metodo === 'POST') {
            CarrinhoController::limpar();
        } else {
            http_response_code(405);
        }
        break;
    case 'carrinho/finalizar':
        if ($metodo === 'POST') {
            CarrinhoController::finalizar();
        } else {
            http_response_code(405);
        }
        break;

    default:
        http_response_code(404);
        echo 'Rota não encontrada';
        break;
}
