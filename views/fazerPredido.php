<?php ob_start(); ?>
<h2>Fazer Pedido</h2>
<?php
$pratosFile = __DIR__ . '/../storage/pratos.json';
$pratos = is_file($pratosFile) ? json_decode(file_get_contents($pratosFile), true) : [];
if (isset($_GET['ok'])) echo '<div style="color:green">Pedido enviado</div>';
?>
<form method="post" action="/?r=pedidos/store">
  <fieldset><legend>Tipo</legend>
    <label><input type="radio" name="tipo" value="entrega" checked> Entrega</label>
    <label><input type="radio" name="tipo" value="retirada"> Retirada</label>
  </fieldset>

  <div><label>Nome: <input name="cliente[nome]" required></label></div>
  <div id="enderecoRow"><label>Endereço: <input name="cliente[endereco]"></label></div>

  <h3>Pratos</h3>
  <?php if (empty($pratos)): ?>
    <p>Nenhum prato.</p>
  <?php else: foreach ($pratos as $p): ?>
    <label><input type="checkbox" name="id_prato[]" value="<?=htmlspecialchars($p['id'] ?? '')?>"> <?=htmlspecialchars($p['nome'] ?? '')?></label><br>
  <?php endforeach; endif; ?>

  <div style="margin-top:10px"><button type="submit">Enviar</button></div>
</form>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
