<?php
require_once __DIR__ . '/../../config/database.php';

class ResponsavelProducao {
    private $conn;
    private $table = 'responsavel_producao';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($pedidoId, $tipo, $responsavel) {
    $sql = "INSERT INTO {$this->table} (pedido_id, tipo, responsavel, data_registro)
            VALUES (:pedido_id, :tipo, :responsavel, NOW())";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':pedido_id', $pedidoId);
    $stmt->bindValue(':tipo', $tipo);
    $stmt->bindValue(':responsavel', $responsavel);
    $stmt->execute();
}


    public function listarPorData($data) {
        $sql = "SELECT responsavel, COUNT(*) as quantidade 
                FROM {$this->table}
                WHERE data_registro = :data
                GROUP BY responsavel
                ORDER BY quantidade DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':data', $data);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function listarPorPeriodo($dataInicio, $dataFim) {
    $sql = "SELECT responsavel, COUNT(*) AS quantidade
            FROM {$this->table}
            WHERE data_registro::date BETWEEN :dataInicio AND :dataFim
            GROUP BY responsavel
            ORDER BY quantidade DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':dataInicio', $dataInicio);
    $stmt->bindValue(':dataFim', $dataFim);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}








}
