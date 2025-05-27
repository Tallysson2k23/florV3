<?php
require_once __DIR__ . '/../../config/database.php';

class Produto {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

 public function salvar($nome, $valor, $codigo) {
    $stmt = $this->db->prepare("INSERT INTO produtos (nome, valor, codigo) VALUES (?, ?, ?)");
    return $stmt->execute([$nome, $valor, $codigo]);
}

    // Lista todos os produtos cadastrados
    public function listarTodos() {
        $stmt = $this->db->query("SELECT * FROM produtos ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
