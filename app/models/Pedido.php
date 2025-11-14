<?php
require_once __DIR__.'/Armazenamento.php';
require_once __DIR__.'/Prato.php';

class Pedido {
    public static function criar(array $dados): void {
        $itensEntrada = $dados['itens'] ?? [];
        if (empty($itensEntrada)) {
            return;
        }

        $mapaPratos = [];
        foreach (Prato::listar() as $prato) {
            $mapaPratos[$prato['id']] = $prato;
        }

        $itensPedido = [];
        $total = 0.0;

        foreach ($itensEntrada as $item) {
            $pratoId = (int) ($item['prato_id'] ?? 0);
            $quantidade = max(1, (int) ($item['quantidade'] ?? 1));
            if (!isset($mapaPratos[$pratoId])) {
                continue;
            }
            $prato = $mapaPratos[$pratoId];
            $subtotal = $prato['preco'] * $quantidade;
            $itensPedido[] = [
                'prato_id' => $prato['id'],
                'nome' => $prato['nome'],
                'preco_unitario' => $prato['preco'],
                'quantidade' => $quantidade,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        if (empty($itensPedido)) {
            return;
        }

        $clienteNome = trim((string) ($dados['cliente_nome'] ?? ''));
        if ($clienteNome === '') {
            return;
        }

        $tipoEntrega = in_array($dados['tipo_entrega'] ?? 'retirada', ['retirada', 'entrega'], true)
            ? $dados['tipo_entrega']
            : 'retirada';
        $clienteEndereco = $dados['cliente_endereco'] ?? null;
        if ($tipoEntrega === 'entrega') {
            $clienteEndereco = trim((string) $clienteEndereco);
            if ($clienteEndereco === '') {
                $clienteEndereco = null;
            }
        } else {
            $clienteEndereco = null;
        }

        $pedidos = Armazenamento::carregar('pedidos');
        $pedidos[] = [
            'id' => Armazenamento::proximoId($pedidos),
            'cliente_nome' => $clienteNome,
            'cliente_endereco' => $clienteEndereco,
            'tipo_entrega' => $tipoEntrega,
            'itens' => $itensPedido,
            'total' => $total,
            'status' => 'novo',
            'criado_em' => Armazenamento::agora(),
        ];
        Armazenamento::salvar('pedidos', $pedidos);
    }

    public static function listar(): array {
        $pedidos = Armazenamento::carregar('pedidos');
        $mapaPratos = [];
        foreach (Prato::listar() as $prato) {
            $mapaPratos[$prato['id']] = $prato;
        }

        foreach ($pedidos as &$pedido) {
            if (!isset($pedido['itens']) || !is_array($pedido['itens'])) {
                $pedido['itens'] = [];
            }

            if (empty($pedido['itens']) && isset($pedido['prato_id'])) {
                $pratoId = (int) $pedido['prato_id'];
                $prato = $mapaPratos[$pratoId] ?? ['nome' => 'Desconhecido', 'preco' => 0];
                $pedido['itens'][] = [
                    'prato_id' => $pratoId,
                    'nome' => $prato['nome'],
                    'preco_unitario' => $prato['preco'],
                    'quantidade' => 1,
                    'subtotal' => $prato['preco'],
                ];
            }

            if (!isset($pedido['total'])) {
                $pedido['total'] = 0.0;
                foreach ($pedido['itens'] as $item) {
                    $pedido['total'] += $item['subtotal'] ?? 0;
                }
            }
        }

        usort($pedidos, fn($a, $b) => $b['id'] <=> $a['id']);
        return $pedidos;
    }

    public static function definirStatus(int $id, string $status): void {
        $pedidos = Armazenamento::carregar('pedidos');
        foreach ($pedidos as &$pedido) {
            if ($pedido['id'] === $id) {
                $pedido['status'] = $status;
            }
        }
        Armazenamento::salvar('pedidos', $pedidos);
    }
}
