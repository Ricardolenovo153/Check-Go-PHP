<?php
// Check-Go/php-backend/middleware/AuthMiddleware.php

class AuthMiddleware {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isAuthenticated() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public function authorize($roles = []) {
        if (!$this->isAuthenticated()) {
            header('Location: ../public/login.php');
            exit();
        }

        $user = $_SESSION['user'];
        
        // Verificação robusta do cargo
        if (!isset($user['role']) || !in_array($user['role'], $roles)) {
            // Mensagem personalizada solicitada para Colaboradores
            if (isset($user['role']) && $user['role'] === 'Colaborador') {
                die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
                        <h2 style='color:red;'>Não tem privilégios suficientes para aceder a esta página.</h2>
                        <p><a href='../public/login.php'>Voltar ao Login</a></p>
                     </div>");
            }
            
            header('Location: ../public/login.php?error=unauthorized');
            exit();
        }

        return $user;
    }
}
?>