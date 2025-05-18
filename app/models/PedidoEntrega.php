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
 endereco, numero_endereco, bairro, referencia, produtos, adicionais, data_abertura, hora, status, ordem_fila)
VALUES 
(:numero_pedido, :tipo, :remetente, :telefone_remetente, :destinatario, :telefone_destinatario,
 :endereco, :numero_endereco, :bairro, :referencia, :produtos, :adicionais, :data_abertura, :hora, :status, :ordem_fila)";


        $stmt = $this->conn->prepare($sql);

        $dados['status'] = 'Pendente';
        $dados['ordem_fila'] = $ordem;
        $dados['hora'] = date('H:i:s');


        return $stmt->execute($dados);
    }

    public function buscar($termo) {
    $sql = "SELECT id, numero_pedido, tipo, remetente AS nome, telefone_remetente AS telefone, produtos, data_abertura
        FROM {$this->table}
        WHERE numero_pedido ILIKE :termo OR remetente ILIKE :termo
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

public function listarTodos() {
    $sql = "SELECT id, numero_pedido, tipo, remetente AS nome, status, data_abertura, hora
            FROM {$this->table}";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function atualizarStatus($id, $status) {
    $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':status' => $status, ':id' => $id]);
}


}
