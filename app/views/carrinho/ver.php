<div class="card">
  <?php if (empty($itens)): ?>
    <p>Seu carrinho está vazio por enquanto.</p>
    <a class="btn" href="/">Ver cardápio</a>
  <?php else: ?>
    <table class="table">
      <tr><th>Prato</th><th>Quantidade</th><th>Preço</th><th>Subtotal</th><th>Ações</th></tr>
      <?php foreach ($itens as $item): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($item['nome']) ?></strong><br>
            <small><?= htmlspecialchars($item['descricao']) ?></small>
          </td>
          <td>
            <form class="js-form-quantidade flex-row" method="post" action="/?r=carrinho/atualizar">
              <input type="hidden" name="prato_id" value="<?= (int) $item['prato_id'] ?>">
              <input type="hidden" name="redirect" value="/?r=carrinho">
              <input
                class="input js-quantidade w-80"
                name="quantidade"
                type="number"
                min="0"
                value="<?= (int) $item['quantidade'] ?>"
                data-preco="<?= htmlspecialchars(number_format($item['preco'], 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>"
                data-subtotal-id="subtotal-<?= (int) $item['prato_id'] ?>"
              >
              <button class="btn">Atualizar</button>
            </form>
          </td>
          <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
          <td>
            <span
              class="js-subtotal"
              id="subtotal-<?= (int) $item['prato_id'] ?>"
              data-valor="<?= htmlspecialchars(number_format($item['subtotal'], 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>"
            >
              R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
            </span>
          </td>
          <td>
            <form method="post" action="/?r=carrinho/remover" class="inline">
              <input type="hidden" name="prato_id" value="<?= (int) $item['prato_id'] ?>">
              <input type="hidden" name="redirect" value="/?r=carrinho">
              <button class="btn" onclick="return confirm('Remover este prato do carrinho?')">Remover</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
    <p>
      <strong>
        Total:
        <span
          id="js-total"
          data-valor="<?= htmlspecialchars(number_format($total, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>"
        >
          R$ <?= number_format($total, 2, ',', '.') ?>
        </span>
      </strong>
    </p>
    <form method="post" action="/?r=carrinho/limpar" class="inline">
      <input type="hidden" name="redirect" value="/?r=carrinho">
      <button class="btn" onclick="return confirm('Esvaziar carrinho?')">Esvaziar carrinho</button>
    </form>

    <script>
      (function () {
        const formatador = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
        const totalSpan = document.getElementById('js-total');

        const atualizarTotal = () => {
          if (!totalSpan) return;
          let total = 0;
          document.querySelectorAll('.js-subtotal').forEach((el) => {
            const valor = Number(el.dataset.valor || '0');
            if (!Number.isNaN(valor)) total += valor;
          });
          totalSpan.dataset.valor = total.toFixed(2);
          totalSpan.textContent = formatador.format(total);
        };

        document.querySelectorAll('.js-quantidade').forEach((input) => {
          const preco = Number(input.dataset.preco || '0');
          const subtotalId = input.dataset.subtotalId || '';
          const subtotalEl = subtotalId ? document.getElementById(subtotalId) : null;
          if (!subtotalEl || Number.isNaN(preco)) return;

          const atualizarSubtotal = () => {
            const quantidade = Math.max(0, Number(input.value || '0'));
            const subtotal = quantidade * preco;
            subtotalEl.dataset.valor = subtotal.toFixed(2);
            subtotalEl.textContent = formatador.format(subtotal);
            atualizarTotal();
          };

          input.addEventListener('input', atualizarSubtotal);
          input.addEventListener('change', () => {
            atualizarSubtotal();
            const form = input.form;
            if (form) {
              if (typeof form.requestSubmit === 'function') form.requestSubmit();
              else form.submit();
            }
          });
        });

        atualizarTotal();
      })();
    </script>
  <?php endif; ?>
</div>

<?php if (!empty($itens)): ?>
  <div class="card">
    <h3>Finalizar pedido</h3>
    <form method="post" action="/?r=carrinho/finalizar">
      <label>Seu nome</label>
      <input class="input" name="cliente_nome" value="<?= htmlspecialchars($checkout['cliente_nome']) ?>" required>

      <label>Tipo de entrega</label>
      <select
        name="tipo_entrega"
        class="input"
        required
        onchange="
          const div = document.getElementById('campo-endereco');
          if (this.value === 'entrega') div.classList.remove('hidden');
          else div.classList.add('hidden');
        "
      >
        <option value="retirada" <?= $checkout['tipo_entrega'] === 'retirada' ? 'selected' : '' ?>>Retirar no local</option>
        <option value="entrega" <?= $checkout['tipo_entrega'] === 'entrega' ? 'selected' : '' ?>>Entrega</option>
      </select>

      <div id="campo-endereco" class="<?= $checkout['tipo_entrega'] === 'entrega' ? '' : 'hidden' ?>">
        <label>Endereço (para entrega)</label>
        <input class="input" name="cliente_endereco" value="<?= htmlspecialchars($checkout['cliente_endereco']) ?>">
      </div>

      <button class="btn mt-12">Enviar pedido</button>
    </form>
  </div>
<?php endif; ?>
