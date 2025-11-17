<?php
// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Pega os dados do funcionário logado (quando existir)
$funcionario = $_SESSION['usuario'] ?? null;

// Importa o model do carrinho e calcula o total de itens
require_once __DIR__ . '/../../models/Carrinho.php';
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

    $links = [];

    if (!$funcionario) {
      // Visitante
      $links[] = ['href' => '/', 'label' => 'Cardápio'];
      $links[] = ['href' => '/?r=carrinho', 'label' => 'Carrinho' . ($quantidadeCarrinho > 0 ? ' (' . $quantidadeCarrinho . ')' : '')];
      $links[] = ['href' => '/?r=func/entrar', 'label' => 'Área do colaborador'];
    } else {
      // Funcionário autenticado: pode ver pedidos
      $links[] = ['href' => '/?r=pedido/listar', 'label' => 'Pedidos'];

      // Administradores também podem gerenciar pratos
      if (!empty($funcionario['is_admin'])) {
        $links[] = ['href' => '/?r=prato/listar', 'label' => 'Pratos'];
      }

      // Link para sair, exibindo o nome do colaborador
      $nome = trim($funcionario['nome'] ?? '') ?: 'colaborador';
      $links[] = ['href' => '/?r=func/sair', 'label' => 'Sair (' . $nome . ')'];
    }

    ?>

    <nav>
      <?php foreach ($links as $index => $link): ?>
        <!-- Link normal do menu -->
        <a href="<?= htmlspecialchars($link['href']) ?>">
          <?= htmlspecialchars($link['label']) ?>
        </a>
      <?php endforeach; ?>
    </nav>
  </header>

  <main>
    <?php if (!empty($_SESSION['flash'])): ?>
      <!-- Mensagem rápida (flash) para feedback ao usuário -->
      <div class="card">
        <?= htmlspecialchars($_SESSION['flash']); ?>
      </div>
      <?php unset($_SESSION['flash']); // Apaga a mensagem depois de exibir ?>
    <?php endif; ?>

    <!-- Conteúdo específico de cada página -->
    <?= $content ?>
  </main>
</body>

</html>
