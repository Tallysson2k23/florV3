<?php
require_once __DIR__ . '/../../config/database.php';

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
    $sql = "SELECT id, nome, codigo FROM vendedores ORDER BY nome ASC";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
