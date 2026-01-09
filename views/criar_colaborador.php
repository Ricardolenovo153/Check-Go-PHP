<?php
// Check-Go/php-backend/views/criar_colaborador.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/ColaboradorController.php';
require_once __DIR__ . '/../controllers/LojaController.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador', 'Gerente']);

$lojaController = new LojaController();
$lojas = $lojaController->listar();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $colabController = new ColaboradorController();
    
    $dados = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'role' => $_POST['role'],
        'loja_id' => !empty($_POST['loja_id']) ? (int)$_POST['loja_id'] : null,
        'ativo' => true
    ];
    
    if ($colabController->criar($dados)) {
        header('Location: gerir_colaboradores.php');
        exit();
    } else {
        $message = 'Erro ao criar colaborador.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Novo Colaborador - Check-Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link rel="stylesheet" href="../public/css/CRUD/criarServico.css">
</head>

<body>
  <div class="container">
    <header>
      <h1>Novo Colaborador</h1>
      <nav><a href="gerir_colaboradores.php" class="btn-voltar">Voltar</a></nav>
    </header>
    <main>
      <?php if ($message): ?><p class="erro"><?php echo $message; ?></p><?php endif; ?>
      <form method="POST" class="form-crud">
        <div class="form-group">
          <label>Nome:</label>
          <input type="text" name="nome" required>
        </div>
        <div class="form-group">
          <label>Email:</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label>Palavra-passe:</label>
          <input type="password" name="password" required>
        </div>
        <div class="form-group">
          <label>Cargo:</label>
          <select name="role" class="form-control">
            <option value="Colaborador">Colaborador</option>
            <option value="Gerente">Gerente</option>
            <option value="Administrador">Administrador</option>
          </select>
        </div>
        <div class="form-group">
          <label>Loja:</label>
          <select name="loja_id" class="form-control">
            <option value="">Nenhuma</option>
            <?php foreach ($lojas as $l): ?>
            <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['nome'] ?? ''); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn-guardar">Criar Colaborador</button>
      </form>
    </main>
  </div>
</body>

</html>