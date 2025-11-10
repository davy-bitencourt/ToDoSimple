<?php ob_start(); ?>
<h2>Gerenciar Pedidos</h2>

<?php
$file = __DIR__ . '/../storage/pedidos.json';
$pedidos = is_file($file) ? json_decode(file_get_contents($file), true) : [];
// order by created_at desc or id desc
usort($pedidos, function($a,$b){
    $ta = $a['created_at'] ?? '';
    $tb = $b['created_at'] ?? '';
    if ($ta === $tb) return ($b['id'] ?? 0) <=> ($a['id'] ?? 0);
    return strcmp($tb, $ta);
});
?>

<?php if (empty($pedidos)): ?>
  <p>Nenhum pedido registrado.</p>
<?php else: ?>
<table border="1" cellpadding="6" style="border-collapse:collapse;width:100%">
  <tr><th>ID</th><th>Tipo</th><th>Cliente</th><th>Itens</th><th>Status</th><th>Ações</th></tr>
  <?php foreach ($pedidos as $p): ?>
    <tr>
      <td><?=htmlspecialchars($p['id'] ?? '')?></td>
      <td><?=htmlspecialchars($p['tipo'] ?? '')?></td>
      <td>
        <?=htmlspecialchars($p['cliente']['nome'] ?? '')?><br>
        <?php if (($p['tipo'] ?? '') === 'entrega'): ?>
          <small>Endereço: <?=htmlspecialchars($p['cliente']['endereco'] ?? '')?></small>
        <?php endif; ?>
      </td>
      <td>
        <?php
          $pratoNames = [];
          $prFile = __DIR__ . '/../storage/pratos.json';
          $pratos = is_file($prFile) ? json_decode(file_get_contents($prFile), true) : [];
          $map = [];
          foreach ($pratos as $pt) $map[$pt['id'] ?? ''] = $pt['nome'] ?? '';
          foreach ($p['id_prato'] ?? [] as $pid) {
            $pratoNames[] = $map[$pid] ?? ('#'.$pid);
          }
          echo htmlspecialchars(implode(', ', $pratoNames));
        ?>
      </td>
      <td><?=htmlspecialchars($p['status'] ?? 'pendente')?></td>
      <td>
        <form method="post" action="/?r=pedidos/update" style="display:inline">
          <input type="hidden" name="id" value="<?=htmlspecialchars($p['id'] ?? '')?>">
          <select name="status">
            <option value="pendente" <?=($p['status'] ?? '')==='pendente' ? 'selected':''?>>Pendente</option>
            <option value="pronto" <?=($p['status'] ?? '')==='pronto' ? 'selected':''?>>Pronto</option>
          </select>
          <button type="submit">Salvar</button>
        </form>
        <form method="post" action="/?r=pedidos/delete" style="display:inline;margin-left:6px" onsubmit="return confirm('Excluir?')">
          <input type="hidden" name="id" value="<?=htmlspecialchars($p['id'] ?? '')?>">
          <button type="submit">Excluir</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

<?php $content = ob_get_clean();?>
<?php include __DIR__ . '/layout.php'; ?>