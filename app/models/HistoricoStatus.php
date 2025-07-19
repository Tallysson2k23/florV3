<?php

class HistoricoStatus {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ Método para registrar novo status no histórico
public function registrar($pedidoId, $tipo, $status, $mensagem = null) {
    $sql = "INSERT INTO historico_status (pedido_id, tipo_pedido, status, data_hora, mensagem)
            VALUES (:pedido_id, :tipo_pedido, :status, NOW(), :mensagem)";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':pedido_id' => $pedidoId,
        ':tipo_pedido' => $tipo,
        ':status' => $status,
        ':mensagem' => $mensagem
    ]);
}


    // ✅ Método para buscar todos os registros do histórico do pedido
public function buscarPorPedido($pedido_id, $tipo_pedido) {
    $sql = "SELECT status, data_hora, mensagem 
            FROM historico_status 
            WHERE pedido_id = :pedido_id AND tipo_pedido = :tipo_pedido 
            ORDER BY data_hora ASC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':pedido_id' => $pedido_id,
        ':tipo_pedido' => $tipo_pedido
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
