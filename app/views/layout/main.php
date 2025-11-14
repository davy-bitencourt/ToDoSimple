<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
$usuario = $_SESSION['usuario'] ?? null;
require_once __DIR__.'/../../models/Carrinho.php';
$quantidadeCarrinho = Carrinho::quantidadeTotal();
?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $title ?? 'Restaurante' ?></title>
  <link rel="stylesheet" href="/assets/style.css">
</head>

<body>
  <header>
    <h1>Restaurante</h1>
    <?php
      $links = [
        ['href' => '/', 'label' => 'Cardápio'],
        ['href' => '/?r=carrinho', 'label' => 'Carrinho'.($quantidadeCarrinho > 0 ? ' ('.$quantidadeCarrinho.')' : '')],
      ];
      if (!$usuario) {
        $links[] = ['href' => '/?r=func/entrar', 'label' => 'Área do colaborador'];
      } else {
        $links[] = ['href' => '/?r=pedido/listar', 'label' => 'Pedidos'];
        if ($usuario['is_admin']) {
          $links[] = ['href' => '/?r=prato/listar', 'label' => 'Pratos'];
          $links[] = ['href' => '/?r=func/gerenciar', 'label' => 'Funcionários'];
        }
        $links[] = ['href' => '/?r=func/sair', 'label' => 'Sair ('.htmlspecialchars($usuario['nome']).')'];
      }
    ?>
    <nav>
      <?php foreach ($links as $index => $link): ?>
        <?= $index > 0 ? ' | ' : '' ?><a href="<?= $link['href'] ?>"><?= $link['label'] ?></a>
      <?php endforeach; ?>
    </nav>
  </header>

  <main>
    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="card"><?= htmlspecialchars($_SESSION['flash']);
      unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    <?= $content ?>
  </main>
</body>

</html>
