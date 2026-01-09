<?php
// Check-Go/php-backend/views/editar_servico.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/ServicoController.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador', 'Gerente']);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: gerir_servicos.php'); exit(); }

$controller = new ServicoController();
$servico = $controller->obter($id);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'],
        'descricao' => $_POST['descricao']
    ];
    
    if ($controller->atualizar($id, $dados)) {
        header('Location: gerir_servicos.php');
        exit();
    } else {
        $message = 'Erro ao atualizar serviço.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Editar Serviço - Check-Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link rel="stylesheet" href="../public/css/CRUD/updateServico.css">
</head>

<body>
  <div class="container">
    <header>
      <h1>Editar Serviço</h1>
      <nav><a href="gerir_servicos.php" class="btn-voltar">Voltar</a></nav>
    </header>
    <main>
      <?php if ($message): ?><p class="erro"><?php echo $message; ?></p><?php endif; ?>
      <form method="POST" class="form-crud">
        <div class="form-group">
          <label>Nome do Serviço:</label>
          <input type="text" name="nome" value="<?php echo htmlspecialchars($servico['nome'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Descrição:</label>
          <textarea name="descricao" rows="4"><?php echo htmlspecialchars($servico['descricao'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn-guardar">Atualizar Serviço</button>
      </form>
    </main>
  </div>
</body>

</html>