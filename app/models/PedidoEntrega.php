<?php
require_once __DIR__ . '/../../config/database.php';

class PedidoEntrega {
    private $conn;
    private $table = 'pedidos_entrega';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $stmt = $this->conn->query("SELECT MAX(ordem_fila) as max_fila FROM {$this->table}");
        $maxOrdem = $stmt->fetch(PDO::FETCH_ASSOC)['max_fila'] ?? 0;
        $ordem = $maxOrdem + 1;

        $sql = "INSERT INTO {$this->table} 
        (numero_pedido, tipo, remetente, telefone_remetente, destinatario, telefone_destinatario,
         endereco, numero_endereco, bairro, referencia, produtos, adicionais, data_abertura, status, ordem_fila)
        VALUES 
        (:numero_pedido, :tipo, :remetente, :telefone_remetente, :destinatario, :telefone_destinatario,
         :endereco, :numero_endereco, :bairro, :referencia, :produtos, :adicionais, :data_abertura, :status, :ordem_fila)";

        $stmt = $this->conn->prepare($sql);

        $dados['status'] = 'Pendente';
        $dados['ordem_fila'] = $ordem;

        return $stmt->execute($dados);
    }
}
