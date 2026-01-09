<?php
// Check-Go/php-backend/views/gerir_colaboradores.php

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/ColaboradorController.php';
require_once __DIR__ . '/layout_sidebar.php';

$auth = new AuthMiddleware();
$user = $auth->authorize(['Administrador', 'Gerente']);

$controller = new ColaboradorController();

if (isset($_GET['eliminar'])) {
    $controller->eliminar($_GET['eliminar']);
    header('Location: gerir_colaboradores.php');
    exit();
}

$colaboradores = $controller->listar($user);
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Colaboradores - Check&Go</title>
  <link rel="stylesheet" href="../public/css/layoutAdmin.css">
  <link rel="stylesheet" href="../public/css/CRUD/lista.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
  /* Ajustes específicos para bater com a imagem */
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

  .status-ativo {
    color: #2fbf71;
    font-weight: 600;
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
    <?php renderSidebar($user, 'colaboradores'); ?>

    <main class="area-conteudo">
      <div class="cabecalho-pagina">
        <h1 class="titulo-pagina">Lista de Colaboradores</h1>
        <a href="criar_colaborador.php" class="btn-adicionar">Adicionar Colaborador</a>
      </div>

      <table class="tabela-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Loja</th>
            <th>Role</th>
            <th>Estado</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($colaboradores)): ?>
          <?php foreach ($colaboradores as $c): ?>
          <tr>
            <td><?php echo htmlspecialchars($c['id']); ?></td>
            <td style="font-weight: 600;"><?php echo htmlspecialchars($c['nome']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?></td>
            <td><?php echo htmlspecialchars($c['loja_nome'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($c['role']); ?></td>
            <td class="status-ativo"><?php echo $c['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
            <td>
              <div class="acoes-icones">
                <a href="editar_colaborador.php?id=<?php echo $c['id']; ?>" class="btn-acao" title="Editar">✏️</a>
                <a href="gerir_colaboradores.php?eliminar=<?php echo $c['id']; ?>" class="btn-acao" title="Eliminar"
                  onclick="return confirm('Tem a certeza?')">🗑️</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px;">Nenhum colaborador encontrado.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>

</html>