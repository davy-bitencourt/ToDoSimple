<?php
require_once __DIR__.'/Prato.php';

class Carrinho {
    private static function garantirSessao(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    private static function carregar(): array {
        self::garantirSessao();
        if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        return $_SESSION['carrinho'];
    }

    private static function salvar(array $itens): void {
        self::garantirSessao();
        $_SESSION['carrinho'] = $itens;
    }

    public static function limpar(): void {
        self::garantirSessao();
        $_SESSION['carrinho'] = [];
    }

    public static function adicionar(int $pratoId, int $quantidade = 1): void {
        if ($quantidade <= 0) {
            return;
        }
        $itens = self::carregar();
        if (isset($itens[$pratoId])) {
            $itens[$pratoId] += $quantidade;
        } else {
            $itens[$pratoId] = $quantidade;
        }
        self::salvar($itens);
    }

    public static function atualizarQuantidade(int $pratoId, int $quantidade): void {
        if ($quantidade <= 0) {
            return;
        }

        $itens = self::carregar();
        $itens[$pratoId] = $quantidade;
        self::salvar($itens);
    }

    public static function remover(int $pratoId): void {
        $itens = self::carregar();
        if (isset($itens[$pratoId])) {
            unset($itens[$pratoId]);
        }
        self::salvar($itens);
    }

    public static function quantidadeTotal(): int {
        return array_sum(self::carregar());
    }

    public static function itensDetalhados(): array {
        $itens = self::carregar();
        if (empty($itens)) {
            return [];
        }

        $pratos = [];
        foreach (Prato::listar() as $prato) {
            $pratos[$prato['id']] = $prato;
        }

        $detalhados = [];
        foreach ($itens as $pratoId => $quantidade) {
            if (!isset($pratos[$pratoId])) {
                continue;
            }
            $prato = $pratos[$pratoId];
            $subtotal = $prato['preco'] * $quantidade;
            $detalhados[] = [
                'prato_id' => $pratoId,
                'nome' => $prato['nome'],
                'descricao' => $prato['descricao'],
                'preco' => $prato['preco'],
                'quantidade' => $quantidade,
                'subtotal' => $subtotal,
            ];
        }

        return $detalhados;
    }

    public static function total(): float {
        $total = 0.0;
        foreach (self::itensDetalhados() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}
