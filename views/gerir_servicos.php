<?php
// Check-Go/php-backend/views/gerir_servicos.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/ServicoController.php';
require_once __DIR__ . '/layout_sidebar.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador', 'Gerente']);

$controller = new ServicoController();

if (isset($_GET['eliminar'])) {
    $controller->eliminar($_GET['eliminar']);
    header('Location: gerir_servicos.php');
    exit();
}

$servicos = $controller->listar();
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Serviços - Check&Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link rel="stylesheet" href="../public/css/CRUD/lista.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
  .area-conteudo {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
  }

  .cabecalho-pagina {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
  }

  .titulo-pagina {
    font-size: 24px;
    font-weight: 700;
  }

  .btn-adicionar {
    background: var(--cor-principal);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
  }

  .tabela-custom {
    width: 100%;
    border-collapse: collapse;
    background: white;
  }

  .tabela-custom th {
    text-align: left;
    padding: 12px;
    color: var(--cor-texto-suave);
    font-size: 12px;
    text-transform: uppercase;
    border-bottom: 1px solid #eee;
  }

  .tabela-custom td {
    padding: 15px 12px;
    border-bottom: 1px solid #f9f9f9;
    font-size: 14px;
  }

  .acoes-icones {
    display: flex;
    gap: 10px;
  }

  .btn-acao {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    text-decoration: none;
  }
  </style>
</head>

<body>
  <div class="layout-principal">
    <?php renderSidebar($user, 'servicos'); ?>

    <main class="area-conteudo">
      <div class="cabecalho-pagina">
        <h1 class="titulo-pagina">Lista de Serviços</h1>
        <a href="criar_servico.php" class="btn-adicionar">Adicionar Serviço</a>
      </div>

      <table class="tabela-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($servicos)): ?>
          <?php foreach ($servicos as $s): ?>
          <tr>
            <td><?php echo htmlspecialchars($s['id']); ?></td>
            <td style="font-weight: 600;"><?php echo htmlspecialchars($s['nome']); ?></td>
            <td><?php echo htmlspecialchars($s['descricao'] ?? 'N/A'); ?></td>
            <td>
              <div class="acoes-icones">
                <a href="editar_servico.php?id=<?php echo $s['id']; ?>" class="btn-acao" title="Editar">✏️</a>
                <a href="gerir_servicos.php?eliminar=<?php echo $s['id']; ?>" class="btn-acao" title="Eliminar"
                  onclick="return confirm('Tem a certeza?')">🗑️</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr>
            <td colspan="4" style="text-align: center; padding: 40px;">Nenhum serviço encontrado.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>

</html>