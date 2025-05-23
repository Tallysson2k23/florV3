<?php
class Vendedor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function salvar($nome, $telefone) {
        $stmt = $this->conn->prepare("INSERT INTO vendedores (nome, telefone) VALUES (:nome, :telefone)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        return $stmt->execute();
    }

    public function listarTodos() {
        $stmt = $this->conn->query("SELECT * FROM vendedores ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
