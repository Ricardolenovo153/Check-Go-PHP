<?php
// Check-Go/php-backend/views/editar_colaborador.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/ColaboradorController.php';
require_once __DIR__ . '/../controllers/LojaController.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador', 'Gerente']);

$colabController = new ColaboradorController();
$lojaController = new LojaController();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: gerir_colaboradores.php');
    exit();
}

$colab = $colabController->obter($id, $user);
if (!$colab || isset($colab['error'])) {
    die($colab['error'] ?? 'Colaborador não encontrado.');
}

$lojas = $lojaController->listar();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'loja_id' => !empty($_POST['loja_id']) ? (int)$_POST['loja_id'] : null,
        'ativo' => isset($_POST['ativo'])
    ];
    
    if ($colabController->atualizar($id, $dados)) {
        header('Location: gerir_colaboradores.php');
        exit();
    } else {
        $message = 'Erro ao atualizar colaborador.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Editar Colaborador - Check-Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link rel="stylesheet" href="../public/css/CRUD/updateServico.css">
</head>

<body>
  <div class="container">
    <header>
      <h1>Editar Colaborador</h1>
      <nav><a href="gerir_colaboradores.php" class="btn-voltar">Voltar</a></nav>
    </header>
    <main>
      <?php if ($message): ?><p class="erro"><?php echo $message; ?></p><?php endif; ?>
      <form method="POST" class="form-crud">
        <div class="form-group">
          <label>Nome:</label>
          <input type="text" name="nome" value="<?php echo htmlspecialchars($colab['nome'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Email:</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($colab['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Cargo:</label>
          <select name="role" class="form-control">
            <option value="Administrador" <?php echo ($colab['role'] ?? '') === 'Administrador' ? 'selected' : ''; ?>>
              Administrador</option>
            <option value="Gerente" <?php echo ($colab['role'] ?? '') === 'Gerente' ? 'selected' : ''; ?>>Gerente
            </option>
            <option value="Colaborador" <?php echo ($colab['role'] ?? '') === 'Colaborador' ? 'selected' : ''; ?>>
              Colaborador</option>
          </select>
        </div>
        <div class="form-group">
          <label>Loja:</label>
          <select name="loja_id" class="form-control">
            <option value="">Nenhuma</option>
            <?php foreach ($lojas as $l): ?>
            <option value="<?php echo $l['id']; ?>"
              <?php echo ($colab['loja_id'] ?? '') == $l['id'] ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($l['nome'] ?? ''); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>
            <input type="checkbox" name="ativo"
              <?php echo (isset($colab['ativo']) && $colab['ativo']) ? 'checked' : ''; ?>> Ativo
          </label>
        </div>
        <button type="submit" class="btn-guardar">Atualizar Colaborador</button>
      </form>
    </main>
  </div>
</body>

</html>