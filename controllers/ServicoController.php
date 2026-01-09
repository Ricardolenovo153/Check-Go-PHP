<?php
// Check-Go/php-backend/controllers/ServicoController.php

require_once __DIR__ . '/../config/database.php';

class ServicoController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listar() {
        if ($this->db === null) return [];
        try {
            $query = "SELECT id, nome, descricao FROM servicos ORDER BY id ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obter($id) {
        if ($this->db === null) return null;
        try {
            $query = "SELECT id, nome, descricao FROM servicos WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function criar($dados) {
        if ($this->db === null) return false;
        try {
            $query = "INSERT INTO servicos (nome, descricao) VALUES (:nome, :descricao)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($dados);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function atualizar($id, $dados) {
        if ($this->db === null) return false;
        try {
            $query = "UPDATE servicos SET nome = :nome, descricao = :descricao WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $dados['id'] = $id;
            return $stmt->execute($dados);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        if ($this->db === null) return false;
        try {
            $query = "DELETE FROM servicos WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>