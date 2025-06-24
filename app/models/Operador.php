<?php
require_once __DIR__ . '/../../config/database.php';

class Operador {
    private $conn;
    private $table = 'operadores';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
    // Se for string, converte em array
    if (is_string($dados)) {
        $dados = ['nome' => $dados];
    }

    $sql = "INSERT INTO {$this->table} (nome) VALUES (:nome)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':nome', $dados['nome']);
    $stmt->execute();
    return $this->conn->lastInsertId();
}


    public function listarTodos() {
        $sql = "SELECT * FROM {$this->table} ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
