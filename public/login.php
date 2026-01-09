<?php
// Check-Go/php-backend/public/login.php

// Define o caminho correto para o controlador
require_once __DIR__ . '/../controllers/AuthController.php';

$message = '';
$email = ''; // Inicializa a variável para repor no form em caso de erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth = new AuthController();
    $result = $auth->login($email, $password);

    if ($result['resultado']) {
        // Redireciona para o dashboard se o login for bem sucedido
        header('Location: ../views/admin_dashboard.php');
        exit();
    } else {
        $message = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Check&Go</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css" />

  <link rel="stylesheet" href="css/layout.css" />
  <link rel="stylesheet" href="css/login.css" />

  <style>
  .error-msg {
    background-color: #ffe6e6;
    color: #d63031;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    border: 1px solid #fab1a0;
    text-align: center;
    font-size: 0.9em;
  }
  </style>
</head>

<body>
  <header>
    <div class="container-banner">
      <div class="logo">
        <img src="imagens/Check_Go__4_-removebg-preview.png" alt="Logo Check&Go" />
      </div>
      <div class="nav-links">
        <a href="#">Home</a> |
        <a href="login.php" class="contrast">Colaboradores</a>
      </div>
    </div>
  </header>

  <main class="login-container">
    <form class="login-form" method="POST" action="">

      <?php if (!empty($message)): ?>
      <div class="error-msg">
        <?php echo htmlspecialchars($message); ?>
      </div>
      <?php endif; ?>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" placeholder="Introduza o seu email"
        value="<?php echo htmlspecialchars($email); ?>" required />

      <label for="password">Palavra-passe</label>
      <input type="password" name="password" id="password" placeholder="Introduza a sua palavra-passe" required />

      <button type="submit" class="login-btn">Entrar</button>
    </form>
  </main>

</body>

</html>