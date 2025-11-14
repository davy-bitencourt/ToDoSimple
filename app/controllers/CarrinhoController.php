<?php
require_once __DIR__ . '/../models/Carrinho.php';
require_once __DIR__ . '/../models/Prato.php';
require_once __DIR__ . '/../models/Pedido.php';

class CarrinhoController
{
    private static function redirecionar(string $destino): void
    {
        if (strpos($destino, '://') !== false) {
            $destino = '/?r=carrinho';
        }
        header('Location: ' . $destino);
    }

    private static function obterDestino(): string
    {
        $destino = $_POST['redirect'] ?? '/?r=carrinho';
        return is_string($destino) && $destino !== '' && $destino[0] === '/' ? $destino : '/?r=carrinho';
    }

    public static function ver(): void
    {
        $itens = Carrinho::itensDetalhados();
        $total = Carrinho::total();
        $dadosCheckout = $_SESSION['checkout_data'] ?? [];
        unset($_SESSION['checkout_data']);

        $checkout = [
            'cliente_nome' => $dadosCheckout['cliente_nome'] ?? '',
            'tipo_entrega' => $dadosCheckout['tipo_entrega'] ?? 'retirada',
            'cliente_endereco' => $dadosCheckout['cliente_endereco'] ?? '',
        ];

        $title = 'Seu carrinho';
        ob_start();
        include __DIR__ . '/../views/carrinho/ver.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/main.php';
    }

    public static function adicionar(): void
    {
        $pratoId = (int) ($_POST['prato_id'] ?? 0);
        $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));
        $prato = Prato::buscar($pratoId);
        if (!$prato) {
            $_SESSION['flash'] = 'Prato inexistente.';
            self::redirecionar(self::obterDestino());
            return;
        }
        Carrinho::adicionar($pratoId, $quantidade);
        $_SESSION['flash'] = 'Prato adicionado ao carrinho.';
        self::redirecionar(self::obterDestino());
    }

    public static function atualizar(): void
    {
        $pratoId = (int) ($_POST['prato_id'] ?? 0);
        $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));
        Carrinho::atualizarQuantidade($pratoId, $quantidade);
        $_SESSION['flash'] = 'Quantidade atualizada.';
        self::redirecionar(self::obterDestino());
    }

    public static function remover(): void
    {
        $pratoId = (int) ($_POST['prato_id'] ?? 0);
        Carrinho::remover($pratoId);
        $_SESSION['flash'] = 'Item removido.';
        self::redirecionar(self::obterDestino());
    }

    public static function limpar(): void
    {
        Carrinho::limpar();
        $_SESSION['flash'] = 'Carrinho esvaziado.';
        self::redirecionar(self::obterDestino());
    }

    public static function finalizar(): void
    {
        $itens = Carrinho::itensDetalhados();
        if (empty($itens)) {
            $_SESSION['flash'] = 'O carrinho está vazio.';
            self::redirecionar('/?r=carrinho');
            return;
        }

        $clienteNome = trim($_POST['cliente_nome'] ?? '');
        $tipoEntrega = $_POST['tipo_entrega'] ?? '';
        $clienteEndereco = trim($_POST['cliente_endereco'] ?? '');

        $_SESSION['checkout_data'] = [
            'cliente_nome' => $clienteNome,
            'tipo_entrega' => $tipoEntrega,
            'cliente_endereco' => $clienteEndereco,
        ];

        if ($clienteNome === '') {
            $_SESSION['flash'] = 'Informe seu nome.';
            self::redirecionar('/?r=carrinho');
            return;
        }
        if (!in_array($tipoEntrega, ['retirada', 'entrega'], true)) {
            $_SESSION['flash'] = 'Selecione um tipo de entrega válido.';
            self::redirecionar('/?r=carrinho');
            return;
        }
        if ($tipoEntrega === 'entrega' && $clienteEndereco === '') {
            $_SESSION['flash'] = 'Informe o endereço para entrega.';
            self::redirecionar('/?r=carrinho');
            return;
        }

        $itensPedido = array_map(
            fn($item) => [
                'prato_id' => $item['prato_id'],
                'quantidade' => $item['quantidade'],
            ],
            $itens
        );

        Pedido::criar([
            'cliente_nome' => $clienteNome,
            'cliente_endereco' => $tipoEntrega === 'entrega' ? $clienteEndereco : null,
            'tipo_entrega' => $tipoEntrega,
            'itens' => $itensPedido,
        ]);

        Carrinho::limpar();
        unset($_SESSION['checkout_data']);
        $_SESSION['flash'] = 'Pedido realizado com sucesso!';
        $_SESSION['flash2'] = 'Nao esqueça o pix!';
        header('Location: /');
    }
}
