<?php ob_start(); ?>
<h2>Novo Funcionário</h2>
<form method="post" action="/?r=funcionarios/store">
  <div><label>Nome: <input name="nome" required autofocus></label></div>
  <div><label>ADM? <input type="checkbox" name="adm_access" value="1"></label></div>
  <div style="margin-top:8px"><button type="submit">Criar</button> <a href="/?r=funcionarios/manage" style="margin-left:8px">Voltar</a></div>
</form>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
