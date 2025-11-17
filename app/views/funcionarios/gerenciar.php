<div class="card">
  <form method="post" action="/?r=func/criar">
    <div class="grid grid2">
      <div>
        <label>Nome</label>
        <input class="input" name="nome" required>
      </div>
      <div>
        <label>E-mail</label>
        <input class="input" name="email" type="email" required>
      </div>
      <div>
        <label>Senha</label>
        <input class="input" name="senha" type="password" required>
      </div>
      <div>
        <label>Administrador?</label>
        <select name="is_admin" class="input">
          <option value="0">Não</option>
          <option value="1">Sim</option>
        </select>
      </div>
    </div>

    <div class="center">
      <button class="btn">Adicionar</button>
    </div>

  </form>
</div>

<div class="center">
  <table class="table">
    <tr>
      <th>Nome</th>
      <th>E-mail</th>
      <th>Administrador</th>
      <th>Ações</th>
    </tr>

    <?php foreach ($funcionarios as $funcionario): ?>
      <tr>
        <td><?= htmlspecialchars($funcionario['nome']) ?></td>
        <td><?= htmlspecialchars($funcionario['email']) ?></td>
        <td><?= $funcionario['is_admin'] ? 'Sim' : 'Não' ?></td>
        <td>
          <form class="inline" method="post" action="/?r=func/excluir">
            <input type="hidden" name="id" value="<?= (int) $funcionario['id'] ?>">
            <button class="btn" onclick="return confirm('Excluir funcionário?')">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
