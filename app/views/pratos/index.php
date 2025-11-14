<a class="btn" href="/?r=prato/novo">Novo prato</a>
<table>
  <tr><th>Nome</th><th>Preço</th><th>Ações</th></tr>
  <?php foreach ($pratos as $prato): ?>
    <tr>
      <td><?= htmlspecialchars($prato['nome']) ?></td>
      <td>R$ <?= number_format($prato['preco'], 2, ',', '.') ?></td>
      <td>
        <a class="btn" href="/?r=prato/editar&id=<?= (int) $prato['id'] ?>">Editar</a>
        <form style="display:inline" method="post" action="/?r=prato/excluir">
          <input type="hidden" name="id" value="<?= (int) $prato['id'] ?>">
          <button class="btn" onclick="return confirm('Excluir prato?')">Excluir</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>