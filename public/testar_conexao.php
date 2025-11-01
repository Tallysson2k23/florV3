<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "✅ Conexão com o banco 'loja' realizada com sucesso!";
} else {
    echo "❌ Erro na conexão com o banco!";
}
?>
