<?php
$tasks  = $data["tasks"] ?? [];
$tarefa = $data["tarefa"] ?? null;

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>ToDoSimple</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/public/style.css">
</head>
<body>
  <div class="container">
    <h1>ToDoSimple</h1>

    <?php if ($view === "home"): ?>
      <form class="form-col" method="post" action="/tasks">
        <label>Título</label>
        <input type="text" name="titulo" placeholder="Nova tarefa..." required>

        <label>Descrição</label>
        <textarea name="descricao" rows="3" placeholder="Detalhes da tarefa"></textarea>

        <input type="submit" value="Adicionar">
      </form>

      <?php if (empty($tasks)): ?>
        <p>(Nenhuma tarefa)</p>
      <?php else: ?>
        <ul class="lista">
          <?php foreach ($tasks as $t): ?>
            <li class="<?php echo !empty($t["feito"]) ? "feito" : ""; ?>">
              <div class="task-body">
                <strong><?php echo e($t["titulo"]); ?></strong>
                <p class="meta <?php echo !empty($t["feito"]) ? "concluida" : "pendente"; ?>">
                  <?php echo !empty($t["feito"]) ? "✅ Concluída" : "⚠️ Pendente"; ?>
                </p>
              </div>

              <div class="actions">
                <a class="btn" href="/tasks/<?php echo (int)$t["id"]; ?>/edit">Editar</a>

                <form method="post" action="/tasks/<?php echo (int)$t["id"]; ?>/delete" class="inline"
                      onsubmit="return confirm(&quot;Deseja excluir esta tarefa? (Esta ação não pode ser desfeita.)&quot;);">
                  <button type="submit" class="btn-danger">Excluir</button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($view === "edit" && $tarefa): ?>
      <p><a href="/" class="link-voltar">Voltar</a></p>
      <h2>Editar tarefa</h2>

      <form class="form-col" method="post" action="/tasks/<?php echo (int)$tarefa["id"]; ?>/update">
        <label>Título</label>
        <input type="text" name="titulo" value="<?php echo e($tarefa["titulo"]); ?>" required>

        <label>Descrição</label>
        <textarea name="descricao" rows="4"><?php echo e($tarefa["descricao"]); ?></textarea>

        <label class="check">
          <input type="checkbox" name="feito" <?php echo !empty($tarefa["feito"]) ? "checked" : ""; ?>>
          Concluída
        </label>

        <div class="form-actions">
          <input type="submit" value="Salvar alterações">
          <a class="btn secondary" href="/">Cancelar</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
