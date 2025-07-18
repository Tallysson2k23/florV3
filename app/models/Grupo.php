<?php
require_once __DIR__ . '/../../config/database.php';

class Grupo {
    private $conn;

    public function __construct($pdo = null) {
        $this->conn = $pdo ?? (new Database())->getConnection();
    }

    public function listarTodosAtivos() {
        $sql = "SELECT id, nome FROM grupos WHERE ativo = TRUE ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodos() {
        $sql = "SELECT id, nome, ativo FROM grupos ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($nome) {
        $sql = "INSERT INTO grupos (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':nome' => $nome]);
    }

    public function inativar($id) {
        $sql = "UPDATE grupos SET ativo = FALSE WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function excluir($id) {
        $sql = "DELETE FROM grupos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

public function ativar($id) {
    $sql = "UPDATE grupos SET ativo = TRUE WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
}






}
