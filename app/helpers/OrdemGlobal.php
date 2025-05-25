<?php
require_once __DIR__ . '/../../config/database.php';


class OrdemGlobal {
    public static function getProximaOrdem() {
        $db = new Database();
        $conn = $db->getConnection();

        // Inicia uma transação para segurança
        $conn->beginTransaction();

        // Seleciona o valor atual
        $stmt = $conn->query("SELECT proxima_ordem FROM ordem_fila_global WHERE id = 1 FOR UPDATE");
        $atual = $stmt->fetch(PDO::FETCH_ASSOC)['proxima_ordem'];

        // Atualiza para o próximo valor
        $stmt = $conn->prepare("UPDATE ordem_fila_global SET proxima_ordem = proxima_ordem + 1 WHERE id = 1");
        $stmt->execute();

        // Confirma a transação
        $conn->commit();

        return $atual;
    }
}
