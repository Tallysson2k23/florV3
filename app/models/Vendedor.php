<?php
require_once __DIR__ . '/../../config/database.php';

class Vendedor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

public function salvar($nome, $telefone) {
    // Buscar o maior código atual (ex: V03)
    $stmt = $this->conn->query("SELECT codigo FROM vendedores ORDER BY id DESC LIMIT 1");
    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ultimo && isset($ultimo['codigo'])) {
        $numeroAtual = (int)substr($ultimo['codigo'], 1); // Remove o 'V' e converte para número
        $proximoNumero = $numeroAtual + 1;
    } else {
        $proximoNumero = 1; // Primeiro código
    }

    // Formata como V01, V02, etc.
    $codigo = 'V' . str_pad($proximoNumero, 2, '0', STR_PAD_LEFT);

    // Inserir com código gerado
    $stmt = $this->conn->prepare("INSERT INTO vendedores (nome, telefone, codigo) VALUES (:nome, :telefone, :codigo)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':codigo', $codigo);
    return $stmt->execute();
}

public function listarTodos() {
    $stmt = $this->conn->prepare("SELECT * FROM vendedores ORDER BY id ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
?>
