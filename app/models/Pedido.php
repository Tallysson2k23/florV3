<?php
require_once __DIR__ . '/../../config/database.php';

class Pedido {
    private $conn;
    private $table_name = "pedidos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function criar($nome, $tipo, $numero_pedido, $quantidade, $produto, $complemento, $obs, $data) {
        $status = 'Pendente';

        // Buscar última ordem
        $stmt = $this->conn->query("SELECT MAX(ordem_fila) as max_fila FROM pedidos");
        $maxOrdem = $stmt->fetch(PDO::FETCH_ASSOC)['max_fila'] ?? 0;
        $novaOrdem = $maxOrdem + 1;

        $query = "INSERT INTO {$this->table_name} 
                  (nome, tipo, numero_pedido, quantidade, produto, complemento, observacao, data_abertura, status, ordem_fila)
                  VALUES (:nome, :tipo, :numero_pedido, :quantidade, :produto, :complemento, :obs, :data, :status, :ordem)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':numero_pedido', $numero_pedido);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':produto', $produto);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':obs', $obs);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':ordem', $novaOrdem);

        return $stmt->execute();
    }
}
