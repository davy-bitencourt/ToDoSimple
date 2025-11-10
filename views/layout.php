<?php
// simple layout — expects $content variable produced by renderView
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>ToDoSimple</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;padding:14px} nav a{margin-right:10px}</style>
</head>
<body>
<nav>
  <?php if (empty($_SESSION['role'])): ?>
    <a href="/?r=auth/login">Entrar</a>

  <?php elseif ($_SESSION['role'] === 'employee'): ?>
    <a href="/?r=pedidos/manage">Gerenciar pedidos</a>

    <?php if (!empty($_SESSION['adm'])): ?>
      <a href="/?r=pratos/manage">Gerenciar pratos</a>
      <a href="/?r=funcionarios/manage">Gerenciar funcionários</a>
    <?php endif; ?>

    <a href="/?r=auth/logout">Sair</a>
  <?php endif; ?>
</nav>

<hr/>
<main>
<?php if (isset($content)) echo $content; else echo '<p>Página</p>'; ?>
</main>
</body>
</html>
