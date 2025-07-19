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

public function ativar($id) {
    $sql = "UPDATE produtos SET ativo = true WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id]);
}

public function inativar($id) {
    $sql = "UPDATE produtos SET ativo = false WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id]);
}

public function buscarPorId($id) {
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function atualizar($id, $nome, $codigo, $valor) {
    $sql = "UPDATE produtos SET nome = :nome, codigo = :codigo, valor = :valor WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':codigo' => $codigo,
        ':valor' => $valor,
        ':id' => $id
    ]);
}

public function listarAtivos() {
    $sql = "SELECT * FROM produtos WHERE ativo = true ORDER BY nome ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





}
