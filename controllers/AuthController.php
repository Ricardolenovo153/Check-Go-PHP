<?php
// Check-Go/php-backend/controllers/AuthController.php

require_once __DIR__ . '/../config/database.php';

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login($email, $password) {
        if ($this->db === null) {
            return ['resultado' => false, 'message' => 'Erro crítico: Ligação à base de dados não estabelecida.'];
        }

        try {
            // Primeiro, vamos descobrir quais colunas existem na tabela users
            $qColumns = "SELECT column_name FROM information_schema.columns WHERE table_name = 'users' AND table_schema = 'public'";
            $stmtCol = $this->db->prepare($qColumns);
            $stmtCol->execute();
            $columns = $stmtCol->fetchAll(PDO::FETCH_COLUMN);

            // Determinar qual o nome da coluna de password (password ou senha)
            $passColumn = in_array('password', $columns) ? 'password' : (in_array('senha', $columns) ? 'senha' : null);

            if (!$passColumn) {
                return ['resultado' => false, 'message' => 'Erro: Coluna de password não encontrada na tabela public.users.'];
            }

            // Agora fazemos o login com a coluna correta
            $query = "SELECT id, nome, email, {$passColumn} as password, role, loja_id, ativo FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($password === $user['password']) {
                    if (isset($user['ativo']) && !$user['ativo']) {
                        return ['resultado' => false, 'message' => 'Conta desativada.'];
                    }

                    if (!isset($_SESSION)) { session_start(); }
                    // Limpar a password da sessão por segurança
                    unset($user['password']);
                    $_SESSION['user'] = $user;
                    return ['resultado' => true, 'user' => $user];
                }
            }
        } catch (PDOException $e) {
            return ['resultado' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()];
        }

        return ['resultado' => false, 'message' => 'Credenciais inválidas.'];
    }

    public function logout() {
        if (!isset($_SESSION)) { session_start(); }
        session_destroy();
        return ['resultado' => true];
    }
}
?>