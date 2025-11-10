<?php ob_start(); ?>
<h2>Gerenciar Pratos e Ingredientes</h2>
<p><a href="/?r=pratos/create">Novo prato</a> | <a href="/?r=igredientes/manage">Ingredientes</a></p>

<h3>Pratos</h3>
<?php if (empty($pratos)): ?><p>Nenhum prato.</p><?php else: ?>
<table border="1" cellpadding="6"><tr><th>ID</th><th>Nome</th><th>Ingredientes</th><th>Ações</th></tr>
<?php foreach ($pratos as $pt): ?>
<tr>
  <td><?=htmlspecialchars($pt['id']??'')?></td>
  <td><?=htmlspecialchars($pt['nome']??'')?></td>
  <td><?=htmlspecialchars(implode(', ',$pt['id_igredientes'] ?? []))?></td>
  <td>
    <a href="/?r=pratos/edit&id=<?=urlencode($pt['id']??'')?>">Editar</a>
    <form method="post" action="/?r=pratos/delete" style="display:inline;margin-left:6px" onsubmit="return confirm('Excluir?')">
      <input type="hidden" name="id" value="<?=htmlspecialchars($pt['id']??'')?>">
      <button type="submit">Excluir</button>
    </form>
  </td>
</tr>
<?php endforeach; ?></table><?php endif; ?>
<?php $content = ob_get_clean();?>
<?php include __DIR__ . '/layout.php'; ?>