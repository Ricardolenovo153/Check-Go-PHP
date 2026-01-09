<?php
// Check-Go/php-backend/views/admin_dashboard.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/layout_sidebar.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador', 'Gerente']);

?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Check&Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
  .area-conteudo {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
  }

  .titulo-pagina {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 30px;
  }

  .welcome-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: var(--sombra-suave);
    border: 1px solid #eee;
  }
  </style>
</head>

<body>
  <div class="layout-principal">
    <?php renderSidebar($user, 'home'); ?>

    <main class="area-conteudo">
      <h1 class="titulo-pagina">Bem-vindo ao Painel de Administração</h1>

      <div class="welcome-card">
        <h2>Olá, <?php echo htmlspecialchars($user['nome']); ?>!</h2>
        <p style="margin-top: 10px; color: #666;">Utilize o menu lateral para gerir as lojas, colaboradores e serviços
          do sistema Check&Go.</p>
      </div>
    </main>
  </div>
</body>

</html>