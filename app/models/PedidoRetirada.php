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
   require_once __DIR__ . '/../helpers/OrdemGlobal.php';
$ordem = OrdemGlobal::getProximaOrdem();


    // Adiciona campos adicionais obrigatórios
    $dados['status'] = 'Pendente';
    $dados['ordem_fila'] = $ordem;
    $dados['hora'] = date('H:i:s');
    $dados['quantidade'] = $dados['quantidade'] ?? 1;

    $dados['vendedor_codigo'] = $dados['vendedor_codigo'] ?? null;


    unset($dados['imprimir']); // se estiver vindo

    $sql = "INSERT INTO {$this->table} 
        (numero_pedido, tipo, nome, telefone, produtos, adicionais, data_abertura, hora, status, ordem_fila, vendedor_codigo, quantidade)
        VALUES 
        (:numero_pedido, :tipo, :nome, :telefone, :produtos, :adicionais, :data_abertura, :hora, :status, :ordem_fila, :vendedor_codigo, :quantidade)";
        
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($dados);

    return $this->conn->lastInsertId();
}



    public function buscar($termo) {
   $sql = "SELECT id, numero_pedido, tipo, nome, telefone, produtos AS produtos, status, data_abertura
        FROM {$this->table}
        WHERE numero_pedido ILIKE :termo OR nome ILIKE :termo
        ORDER BY data_abertura DESC";




    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function buscarPorId($id) {
    $sql = "SELECT p.*, v.nome AS nome_vendedor, v.codigo AS codigo_vendedor
            FROM {$this->table} p
            LEFT JOIN vendedores v ON p.vendedor_codigo = v.codigo
            WHERE p.id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function listarTodos() {
    $sql = "SELECT id, numero_pedido, tipo, nome, status, data_abertura, hora, ordem_fila
            FROM {$this->table}
            ORDER BY ordem_fila DESC";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


public function atualizarStatus($id, $status) {
    $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':status' => $status, ':id' => $id]);
}

public function buscarPorStatus($status) {
    $sql = "SELECT * FROM {$this->table} WHERE status = :status ORDER BY data_abertura DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
