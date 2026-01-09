<?php
// Check-Go/php-backend/controllers/LojaController.php

require_once __DIR__ . '/../config/database.php';

class LojaController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listar() {
        if ($this->db === null) return [];
        try {
            $query = "SELECT l.id, l.nome, l.morada, l.gerente_id, u.nome as gerente_nome 
                      FROM lojas l 
                      LEFT JOIN users u ON l.gerente_id = u.id 
                      ORDER BY l.id ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($lojas as &$loja) {
                $qServicos = "SELECT s.nome 
                              FROM servicos s 
                              JOIN lojas_servicos ls ON s.id = ls.servico_id 
                              WHERE ls.loja_id = :loja_id AND ls.ativo = true";
                $stmtS = $this->db->prepare($qServicos);
                $stmtS->bindParam(':loja_id', $loja['id']);
                $stmtS->execute();
                $loja['servicos'] = $stmtS->fetchAll(PDO::FETCH_COLUMN);
            }
            return $lojas;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obter($id) {
        if ($this->db === null) return null;
        try {
            $query = "SELECT * FROM lojas WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $loja = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($loja) {
                $qServicos = "SELECT servico_id FROM lojas_servicos WHERE loja_id = :loja_id";
                $stmtS = $this->db->prepare($qServicos);
                $stmtS->bindParam(':loja_id', $id);
                $stmtS->execute();
                $loja['servicos_ids'] = $stmtS->fetchAll(PDO::FETCH_COLUMN);
            }
            return $loja;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function listarGerentesDisponiveis() {
        if ($this->db === null) return [];
        try {
            // Selecionar utilizadores que são Gerentes
            $query = "SELECT id, nome FROM users WHERE role = 'Gerente' ORDER BY nome ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function criar($dados, $servicoIds = []) {
        if ($this->db === null) return false;
        try {
            $this->db->beginTransaction();
            
            $query = "INSERT INTO lojas (nome, morada, gerente_id) VALUES (:nome, :morada, :gerente_id)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($dados);
            $lojaId = $this->db->lastInsertId();

            if (!empty($servicoIds)) {
                $qLS = "INSERT INTO lojas_servicos (loja_id, servico_id, ativo) VALUES (:loja_id, :servico_id, true)";
                $stmtLS = $this->db->prepare($qLS);
                foreach ($servicoIds as $sid) {
                    $stmtLS->execute(['loja_id' => $lojaId, 'servico_id' => $sid]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }

    public function atualizar($id, $dados, $servicoIds = []) {
        if ($this->db === null) return false;
        try {
            $this->db->beginTransaction();
            
            $query = "UPDATE lojas SET nome = :nome, morada = :morada, gerente_id = :gerente_id WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $dados['id'] = $id;
            $stmt->execute($dados);

            $qDel = "DELETE FROM lojas_servicos WHERE loja_id = :loja_id";
            $stmtDel = $this->db->prepare($qDel);
            $stmtDel->execute(['loja_id' => $id]);

            if (!empty($servicoIds)) {
                $qLS = "INSERT INTO lojas_servicos (loja_id, servico_id, ativo) VALUES (:loja_id, :servico_id, true)";
                $stmtLS = $this->db->prepare($qLS);
                foreach ($servicoIds as $sid) {
                    $stmtLS->execute(['loja_id' => $id, 'servico_id' => $sid]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }

    public function eliminar($id) {
        if ($this->db === null) return false;
        try {
            $query = "DELETE FROM lojas WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>