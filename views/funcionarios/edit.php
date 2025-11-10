<?php ob_start(); ?>
<?php $f = $f ?? null; ?>
<h2>Editar Funcionário</h2>
<?php if (empty($f)): ?>
  <div style="color:red">Funcionário não encontrado.</div>
  <p><a href="/?r=funcionarios/manage">Voltar</a></p>
<?php else: ?>
<form method="post" action="/?r=funcionarios/update">
  <input type="hidden" name="id" value="<?=htmlspecialchars($f['id']??'')?>">
  <div><label>Nome: <input name="nome" required value="<?=htmlspecialchars($f['nome']??'')?>" autofocus></label></div>
  <div><label>ADM? <input type="checkbox" name="adm_access" value="1" <?=!empty($f['adm_access'])?'checked':''?>></label></div>
  <div style="margin-top:8px"><button type="submit">Salvar</button> <a href="/?r=funcionarios/manage" style="margin-left:8px">Voltar</a></div>
</form>
<?php endif; ?>
<?php $content = ob_get_clean();?>
<?php include __DIR__ . '/layout.php'; ?>