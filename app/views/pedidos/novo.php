<form method="post" action="/?r=pedido/criar">
  <label>Seu nome</label>
  <input class="input" name="cliente_nome" required>

  <label>Tipo de entrega</label>
  <select name="tipo_entrega" class="input" required onchange="document.getElementById('endereco').style.display = this.value === 'entrega' ? 'block' : 'none'">
    <option value="retirada">Retirar no local</option>
    <option value="entrega" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'entrega' ? 'selected' : '' ?>>Entrega</option>
  </select>

  <div id="endereco" style="display: <?= (isset($_GET['tipo']) && $_GET['tipo'] === 'entrega') ? 'block' : 'none' ?>;">
    <label>Endereço (para entrega)</label>
    <input class="input" name="cliente_endereco">
  </div>
  
  <label>Prato</label>
  <select name="prato_id" class="input" required>
    <?php foreach ($pratos as $prato): ?>
      <option value="<?= (int) $prato['id'] ?>" <?= isset($_GET['prato_id']) && $_GET['prato_id'] == $prato['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($prato['nome']) ?> - R$ <?= number_format($prato['preco'], 2, ',', '.') ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button class="btn">Fazer pedido</button>
</form>