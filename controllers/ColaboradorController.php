<?php
// Check-Go/php-backend/controllers/ColaboradorController.php

require_once __DIR__ . '/../config/database.php';

class ColaboradorController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    private function getPasswordColumn() {
        try {
            $qColumns = "SELECT column_name FROM information_schema.columns WHERE table_name = 'users' AND table_schema = 'public'";
            $stmtCol = $this->db->prepare($qColumns);
            $stmtCol->execute();
            $columns = $stmtCol->fetchAll(PDO::FETCH_COLUMN);
            return in_array('password', $columns) ? 'password' : (in_array('senha', $columns) ? 'senha' : 'password');
        } catch (Exception $e) {
            return 'password';
        }
    }

    public function listar($user) {
        if ($this->db === null) return [];
        try {
            $query = "SELECT u.id, u.nome, u.email, u.role, u.loja_id, u.ativo, l.nome as loja_nome 
                      FROM users u 
                      LEFT JOIN lojas l ON u.loja_id = l.id";
            
            if (isset($user['role']) && $user['role'] === 'Gerente' && isset($user['loja_id'])) {
                $query .= " WHERE u.loja_id = :loja_id";
            }
            $query .= " ORDER BY u.id ASC";

            $stmt = $this->db->prepare($query);
            if (isset($user['role']) && $user['role'] === 'Gerente' && isset($user['loja_id'])) {
                $stmt->bindParam(':loja_id', $user['loja_id']);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obter($id, $user) {
        if ($this->db === null) return null;
        try {
            $passCol = $this->getPasswordColumn();
            $query = "SELECT u.*, u.{$passCol} as password, l.nome as loja_nome 
                      FROM users u 
                      LEFT JOIN lojas l ON u.loja_id = l.id 
                      WHERE u.id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $colab = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($colab && isset($user['role']) && $user['role'] === 'Gerente' && $colab['loja_id'] != $user['loja_id']) {
                return ['error' => 'Proibido: Não tem acesso a colaboradores fora da sua loja.'];
            }

            return $colab;
        } catch (PDOException $e) {
            return null;
        }
    } 

}
?>