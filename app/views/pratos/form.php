<form method="post" action="<?= $isEdit ? '/?r=prato/atualizar' : '/?r=prato/criar' ?>">
  <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= (int) $prato['id'] ?>">
  <?php endif; ?>
  <label>Nome</label>
  <input class="input" name="nome" value="<?= htmlspecialchars($prato['nome'] ?? '') ?>" required>
  <label>Preço</label>
  <input class="input" name="preco" type="number" step="0.01" value="<?= htmlspecialchars($prato['preco'] ?? '') ?>" required>
  <label>Descrição</label>
  <textarea class="input" name="descricao" rows="3"><?= htmlspecialchars($prato['descricao'] ?? '') ?></textarea>
  <button class="btn"><?= $isEdit ? 'Salvar' : 'Criar' ?></button>
</form>