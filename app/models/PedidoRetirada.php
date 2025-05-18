<?php
require_once __DIR__ . '/../../config/database.php';

class PedidoRetirada {
    private $conn;
    private $table = 'pedidos_retirada';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $stmt = $this->conn->query("SELECT MAX(ordem_fila) as max_fila FROM {$this->table}");
        $maxOrdem = $stmt->fetch(PDO::FETCH_ASSOC)['max_fila'] ?? 0;
        $ordem = $maxOrdem + 1;

        $sql = "INSERT INTO {$this->table} 
        (numero_pedido, tipo, nome, telefone, produtos, adicionais, data_abertura, status, ordem_fila)
        VALUES 
        (:numero_pedido, :tipo, :nome, :telefone, :produtos, :adicionais, :data_abertura, :status, :ordem_fila)";

        $stmt = $this->conn->prepare($sql);

        $dados['status'] = 'Pendente';
        $dados['ordem_fila'] = $ordem;

        return $stmt->execute($dados);
    }

    public function buscar($termo) {
   $sql = "SELECT id, numero_pedido, tipo, nome, telefone, produtos, data_abertura
        FROM {$this->table}
        WHERE numero_pedido ILIKE :termo OR nome ILIKE :termo
        ORDER BY data_abertura DESC";


    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function buscarPorId($id) {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
