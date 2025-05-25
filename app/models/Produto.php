<?php
require_once __DIR__ . '/../../config/database.php';

class Produto {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function salvar($nome) {
        $stmt = $this->conn->prepare("INSERT INTO produtos (nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    }

    public function listarTodos() {
        $stmt = $this->conn->query("SELECT * FROM produtos ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
