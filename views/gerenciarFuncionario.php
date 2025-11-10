<?php ob_start(); ?>
<h2>Gerenciar Funcionários</h2>
<p><a href="/?r=funcionarios/create">Novo Funcionário</a></p>
<?php if (empty($items)): ?><p>Nenhum funcionário.</p><?php else: ?>
<table border="1" cellpadding="6"><tr><th>ID</th><th>Nome</th><th>ADM</th><th>Ações</th></tr>
<?php foreach ($items as $it): ?>
<tr>
  <td><?=htmlspecialchars($it['id']??'')?></td>
  <td><?=htmlspecialchars($it['nome']??'')?></td>
  <td><?=!empty($it['adm_access']) ? 'Sim':'Não'?></td>
  <td>
    <a href="/?r=funcionarios/edit&id=<?=urlencode($it['id']??'')?>">Editar</a>
    <form method="post" action="/?r=funcionarios/delete" style="display:inline;margin-left:6px" onsubmit="return confirm('Excluir?')">
      <input type="hidden" name="id" value="<?=htmlspecialchars($it['id']??'')?>">
      <button type="submit">Excluir</button>
    </form>
  </td>
</tr>
<?php endforeach; ?></table><?php endif; ?>
<?php $content = ob_get_clean();?>
<?php include __DIR__ . '/layout.php'; ?>