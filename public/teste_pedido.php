<?php
require_once __DIR__ . '/../models/PedidoEntrega.php';

try {
    $pedidoEntrega = new PedidoEntrega();
    $pedidos = $pedidoEntrega->buscarTodos();  // ou qualquer método que busque algo
    echo "<pre>";
    print_r($pedidos);
    echo "</pre>";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
