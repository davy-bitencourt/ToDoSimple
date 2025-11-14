<?php
$statusOpcoes = [
  'novo' => 'Novo',
  'em_preparo' => 'Em preparo',
  'pronto' => 'Pronto',
  'entregue' => 'Entregue',
];
?>
<table>
  <tr><th>#</th><th>Cliente</th><th>Tipo</th><th>Itens</th><th>Total</th><th>Status</th><th>Ações</th></tr>
  <?php foreach ($pedidos as $pedido): ?>
    <?php
      $tipoLegenda = $pedido['tipo_entrega'] === 'retirada' ? 'Retirada' : 'Entrega';
      $statusLegenda = $statusOpcoes[$pedido['status']] ?? ucfirst($pedido['status']);
    ?>
    <tr>
      <td><?= (int) $pedido['id'] ?></td>
      <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
      <td>
        <span class="badge"><?= htmlspecialchars($tipoLegenda) ?></span>
        <?php if ($pedido['tipo_entrega'] === 'entrega' && !empty($pedido['cliente_endereco'])): ?>
          <br><small><?= htmlspecialchars($pedido['cliente_endereco'] ?? '') ?></small>
        <?php endif; ?>
      </td>
      <td>
        <?php foreach ($pedido['itens'] as $item): ?>
          <div><?= (int) $item['quantidade'] ?> × <?= htmlspecialchars($item['nome']) ?> - R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></div>
        <?php endforeach; ?>
      </td>
      <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
      <td><span class="badge"><?= htmlspecialchars($statusLegenda) ?></span></td>
      <td>
        <form style="display:inline" method="post" action="/?r=pedido/atualizar-status">
          <input type="hidden" name="id" value="<?= (int) $pedido['id'] ?>">
          <select name="status">
            <?php foreach ($statusOpcoes as $valor => $rotulo): ?>
              <option value="<?= $valor ?>" <?= $valor === $pedido['status'] ? 'selected' : '' ?>><?= $rotulo ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn">OK</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
