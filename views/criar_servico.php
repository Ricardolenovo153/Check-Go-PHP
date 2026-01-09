<?php
// Check-Go/php-backend/views/criar_servico.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/ServicoController.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador', 'Gerente']);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ServicoController();
    $dados = [
        'nome' => $_POST['nome'],
        'descricao' => $_POST['descricao']
    ];
    
    if ($controller->criar($dados)) {
        header('Location: gerir_servicos.php');
        exit();
    } else {
        $message = 'Erro ao criar serviço.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Criar Serviço - Check-Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link rel="stylesheet" href="../public/css/CRUD/criarServico.css">
</head>

<body>
  <div class="container">
    <header>
      <h1>Novo Serviço</h1>
      <nav><a href="gerir_servicos.php" class="btn-voltar">Voltar</a></nav>
    </header>
    <main>
      <?php if ($message): ?><p class="erro"><?php echo $message; ?></p><?php endif; ?>
      <form method="POST" class="form-crud">
        <div class="form-group">
          <label>Nome do Serviço:</label>
          <input type="text" name="nome" required>
        </div>
        <div class="form-group">
          <label>Descrição:</label>
          <textarea name="descricao" rows="4"></textarea>
        </div>
        <button type="submit" class="btn-guardar">Criar Serviço</button>
      </form>
    </main>
  </div>
</body>

</html>