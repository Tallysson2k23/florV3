<?php
require_once __DIR__ . '/../../config/database.php';

class NotificacaoController {
    public function listarPedidosFuturos() {
        $db = Database::conectar();
        $hoje = date('Y-m-d');
        $resultados = [];

        // Carrega os registros lidos
        $sqlLidas = "SELECT pedido_id, tipo FROM notificacoes_lidas";
        $notificacoesLidas = $db->query($sqlLidas)->fetchAll(PDO::FETCH_ASSOC);
        $lidasMap = [];
        foreach ($notificacoesLidas as $linha) {
            $chave = $linha['tipo'] . '_' . $linha['pedido_id'];
            $lidasMap[$chave] = true;
        }

        // pedidos_entrega
        $stmt1 = $db->prepare("SELECT id, remetente AS nome, produtos, data_abertura 
            FROM pedidos_entrega 
            WHERE data_abertura > :hoje 
              AND status NOT IN ('Cancelado', 'Pronto') 
            ORDER BY id DESC");
        $stmt1->execute(['hoje' => $hoje]);
        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
            $chave = 'entrega_' . $row['id'];
            $dataAbertura = date('Y-m-d', strtotime($row['data_abertura']));

            // Se já foi lido e a data já passou, oculta
            if (isset($lidasMap[$chave]) && $dataAbertura <= $hoje) {
                continue;
            }

            $resultados[] = [
                'id' => $row['id'],
                'tipo' => 'entrega',
                'nome' => $row['nome'] ?? 'Sem nome',
                'produto' => $row['produtos'] ?? '---',
                'data' => date('d/m/Y', strtotime($row['data_abertura'])),
                'lido' => isset($lidasMap[$chave]) // Marca se já foi lido
            ];
        }

        // pedidos_retirada
        $stmt2 = $db->prepare("SELECT id, nome, produtos, data_abertura 
            FROM pedidos_retirada 
            WHERE data_abertura > :hoje 
              AND status NOT IN ('Cancelado', 'Pronto') 
            ORDER BY id DESC");
        $stmt2->execute(['hoje' => $hoje]);
        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $chave = 'retirada_' . $row['id'];
            $dataAbertura = date('Y-m-d', strtotime($row['data_abertura']));

            if (isset($lidasMap[$chave]) && $dataAbertura <= $hoje) {
                continue;
            }

            $resultados[] = [
                'id' => $row['id'],
                'tipo' => 'retirada', // ✅ Corrigido aqui!
                'nome' => $row['nome'] ?? 'Sem nome',
                'produto' => $row['produtos'] ?? '---',
                'data' => date('d/m/Y', strtotime($row['data_abertura'])),
                'lido' => isset($lidasMap[$chave]) // ✅ Adicionado aqui também!
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($resultados);
    }

    public function marcarComoLido() {
        $db = Database::conectar();

        $id = $_POST['id'] ?? null;
        $tipo = $_POST['tipo'] ?? null;

        if (!$id || !$tipo) {
            http_response_code(400);
            echo 'Dados incompletos';
            return;
        }

        // Verifica se já foi lido
        $check = $db->prepare("SELECT 1 FROM notificacoes_lidas WHERE pedido_id = :id AND tipo = :tipo");
        $check->execute(['id' => $id, 'tipo' => $tipo]);
        if (!$check->fetch()) {
            // Inserir
            $insert = $db->prepare("INSERT INTO notificacoes_lidas (pedido_id, tipo) VALUES (:id, :tipo)");
            $insert->execute(['id' => $id, 'tipo' => $tipo]);
        }

        echo 'OK';
    }
}
