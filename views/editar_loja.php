<?php
// Check-Go/php-backend/views/editar_loja.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/LojaController.php';
require_once __DIR__ . '/../controllers/ServicoController.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador']);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: gerir_lojas.php'); exit(); }

$lojaController = new LojaController();
$servicoController = new ServicoController();

$loja = $lojaController->obter($id);
$todosServicos = $servicoController->listar();
$gerentes = $lojaController->listarGerentesDisponiveis();

// Obter os IDs dos serviços que a loja já tem associados
$servicosAtuais = $loja['servicos_ids'] ?? [];

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'],
        'morada' => $_POST['morada'],
        'gerente_id' => !empty($_POST['gerente_id']) ? (int)$_POST['gerente_id'] : null
    ];
    $servicosSelecionados = $_POST['servicos'] ?? [];
    
    if ($lojaController->atualizar($id, $dados, $servicosSelecionados)) {
        header('Location: gerir_lojas.php');
        exit();
    } else {
        $message = 'Erro ao atualizar loja.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Editar Loja - Check-Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link rel="stylesheet" href="../public/css/CRUD/updateServico.css">
</head>

<body>
  <div class="container">
    <header>
      <h1>Editar Loja</h1>
      <nav><a href="gerir_lojas.php" class="btn-voltar">Voltar</a></nav>
    </header>
    <main>
      <?php if ($message): ?><p class="erro"><?php echo $message; ?></p><?php endif; ?>
      <form method="POST" class="form-crud">
        <div class="form-group">
          <label>Nome da Loja:</label>
          <input type="text" name="nome" value="<?php echo htmlspecialchars($loja['nome'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Morada:</label>
          <input type="text" name="morada" value="<?php echo htmlspecialchars($loja['morada'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label>Gerente Responsável:</label>
          <select name="gerente_id" class="form-control"
            style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            <option value="">Selecione um Gerente (Opcional)</option>
            <?php foreach ($gerentes as $g): ?>
            <option value="<?php echo $g['id']; ?>"
              <?php echo ($loja['gerente_id'] ?? '') == $g['id'] ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($g['nome']); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Serviços Disponíveis:</label>
          <div class="checkbox-group"
            style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 5px;">
            <?php foreach ($todosServicos as $s): ?>
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" name="servicos[]" value="<?php echo $s['id']; ?>"
                <?php echo in_array($s['id'], $servicosAtuais) ? 'checked' : ''; ?>>
              <?php echo htmlspecialchars($s['nome'] ?? ''); ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <button type="submit" class="btn-guardar" style="margin-top: 20px;">Atualizar Loja</button>
      </form>
    </main>
  </div>
</body>

</html>