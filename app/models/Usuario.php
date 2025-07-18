<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $conn;
    private $table = 'usuarios';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($nome, $email, $senha, $tipo) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO {$this->table} (nome, email, senha, tipo)
                VALUES (:nome, :email, :senha, :tipo)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $hash,
            ':tipo' => $tipo
        ]);
    }

    public function autenticar($email, $senha) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return null;
    }

public function listarTodos() {
    $sql = "SELECT id, nome, email, tipo, ativo FROM {$this->table} ORDER BY nome ASC";
    $stmt = $this->conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function atualizarAtivo($id, $ativo) {
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET ativo = :ativo WHERE id = :id");

    // Garante que seja 0 ou 1 (número), não string vazia
    $ativo = ($ativo == 1) ? 1 : 0;

    $stmt->execute([
        ':ativo' => $ativo,
        ':id' => $id
    ]);
}
public function buscarPorId($id) {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}





}
