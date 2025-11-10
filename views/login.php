<?php ob_start(); ?>
<h2>Login Funcionário</h2>
<?php if (!empty($_SESSION['login_error'])): ?>
  <div style="color:red"><?=htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']);?></div>
<?php endif; ?>
<form method="post" action="/?r=auth/login">
  <div><label>ID: <input name="id" type="number" min="1" required autofocus></label></div>
  <div style="margin-top:8px"><button type="submit">Entrar</button> <a href="/?r=pedidos/create" style="margin-left:8px">Voltar</a></div>
</form>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
