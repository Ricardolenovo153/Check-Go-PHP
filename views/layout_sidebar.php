<?php
// Check-Go/php-backend/views/layout_sidebar.php

function renderSidebar($user, $activePage) {
    $nome = htmlspecialchars($user['nome'] ?? 'Utilizador');
    $role = htmlspecialchars($user['role'] ?? 'Cargo');
    

    $pathIcons = "/public/imagens/icons/"; 
    $pathLogo  = "/public/imagens/Check_Go__3_-removebg-preview.png"; 
    
    echo '
    <aside class="barra-lateral">
        <div class="cabecalho-lateral">
            <div class="linha-titulo"></div>
            <span class="titulo-lateral">Administração</span>
        </div>

        <div class="cartao-logo">
            <img src="' . $pathLogo . '" alt="Check&Go" class="imagem-logo">
        </div>

        <div class="secao-utilizador">
            <div class="nome-utilizador">' . $nome . '</div>
            <div class="cargo-utilizador">' . $role . '</div>
        </div>

        <nav class="menu-lateral">
            <a href="admin_dashboard.php" class="item-menu ' . ($activePage == 'home' ? 'item-menu-ativo' : '') . '">
                <span class="icone-menu"><img src="' . $pathIcons . 'home.png" alt=""></span>
                <span class="texto-menu">Home</span>
            </a>';

    if ($user['role'] === 'Administrador') {
        echo '
            <a href="gerir_lojas.php" class="item-menu ' . ($activePage == 'lojas' ? 'item-menu-ativo' : '') . '">
                <span class="icone-menu"><img src="' . $pathIcons . 'lojas.png" alt=""></span>
                <span class="texto-menu">Lojas</span>
            </a>';
    }

    echo '
            <a href="gerir_colaboradores.php" class="item-menu ' . ($activePage == 'colaboradores' ? 'item-menu-ativo' : '') . '">
                <span class="icone-menu"><img src="' . $pathIcons . 'colaborador.png" alt=""></span>
                <span class="texto-menu">Colaboradores</span>
            </a>
            
            <a href="gerir_servicos.php" class="item-menu ' . ($activePage == 'servicos' ? 'item-menu-ativo' : '') . '">
                <span class="icone-menu"><img src="' . $pathIcons . 'servicos.png" alt=""></span>
                <span class="texto-menu">Serviços</span>
            </a>
            
        </nav>

        <div class="rodape-lateral">
            <a href="logout.php" class="link-logout">
                <span class="texto-logout">Logout</span>
                <span class="icone-logout"><img src="' . $pathIcons . 'logout.png" alt=""></span>
            </a>
        </div>
    </aside>';
}
?>