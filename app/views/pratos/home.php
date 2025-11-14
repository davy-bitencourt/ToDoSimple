<div class="grid grid2">
  <?php foreach ($pratos as $prato): ?>
    <div class="card">
      <h3><?= htmlspecialchars($prato['nome']) ?></h3>
      <p><?= nl2br(htmlspecialchars($prato['descricao'])) ?></p>
      <p><strong>R$ <?= number_format($prato['preco'], 2, ',', '.') ?></strong></p>
      <form method="post" action="/?r=carrinho/adicionar">
        <input type="hidden" name="prato_id" value="<?= (int) $prato['id'] ?>">
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/') ?>">
        <label for="quantidade-<?= (int) $prato['id'] ?>">Quantidade</label>
        <input class="input" id="quantidade-<?= (int) $prato['id'] ?>" name="quantidade" type="number" min="1" value="1">
        <button class="btn" style="margin-top:8px">Adicionar ao carrinho</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
