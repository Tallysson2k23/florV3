<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}
require_once __DIR__ . '/../../helpers/verifica_login.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acompanhamento do Atendente - Flor de Cheiro</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f4f6;
    padding: 0px;
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h1 {
    color: #111;
    font-size: 24px;
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

form {
    text-align: center;
    margin-bottom: 20px;
}

input[type="date"] {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-top: 10px;
}

.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 90%;
    max-width: 1100px;
    margin-bottom: 30px;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

table th {
    background: #f8f9fa;
    font-weight: bold;
    color: #333;
    font-size: 14px;
    text-transform: uppercase;
}

.status-select {
    padding: 8px 16px;
    font-weight: bold;
    color: white;
    border: none;
    border-radius: 20px;
    appearance: none;
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D'10'%20height%3D'6'%20viewBox%3D'0%200%2010%206'%20fill%3D'none'%20xmlns%3D'http%3A//www.w3.org/2000/svg'%3E%3Cpath%20d%3D'M1%201L5%205L9%201'%20stroke%3D'white'%20stroke-width%3D'2'%20stroke-linecap%3D'round'%20stroke-linejoin%3D'round'/%3E%3C/svg%3E");
    font-size: 14px;
    min-width: 120px;
    cursor: pointer;
    text-align: center;
}

.status-select-pronto {
    background-color: #f59e0b !important;
}

.status-select-entregue {
    background-color: #16a34a !important;
}

h2 {
    font-size: 18px;
    margin-top: 10px;
    margin-bottom: 10px;
    text-align: center;
    color: #222;
    position: relative;
    font-weight: bold;
}

h2::before {
    content: "📋";
    margin-right: 8px;
}

.topo {
    width: 100%;
    background: #111;
    color: white;
    padding: 20px 100px;
    font-family: "Brush Script MT", cursive;
    font-size: 28px;
    text-align: center;
    margin-bottom: 50px;
}
</style>
</head>
<body>
<div class="topo">
    Flor de Cheiro
</div>

<form method="get" action="index.php">
    <input type="hidden" name="rota" value="acompanhamento-atendente">
    <label for="data">Selecionar Data:</label>
    <input type="date" name="data" id="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
</form>

<h1>Acompanhamento do Atendente</h1>

<div class="table-card">
<h2>Pedidos</h2>
<table>
    <tr>
        <th>Nº Pedido</th>
        <th>Cliente</th>
        <th>Tipo</th>
        <th>Status</th>
    </tr>
    <?php
    $todosPedidos = array_merge($entregas, $retiradas);
    usort($todosPedidos, function($a, $b) {
        return ($a['ordem_fila'] ?? 0) - ($b['ordem_fila'] ?? 0);
    });

    foreach ($todosPedidos as $pedido):
    ?>
    <tr>
        <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
        <td>
            <?php if (isset($pedido['destinatario'])): ?>
                <?= htmlspecialchars($pedido['destinatario']) ?>
            <?php else: ?>
                <?= htmlspecialchars($pedido['nome']) ?>
            <?php endif; ?>
        </td>
        <td>
            <?= (isset($pedido['tipo']) && ($pedido['tipo'] === '1-Entrega' || strtolower($pedido['tipo']) === 'entrega')) ? 'Entrega' : 'Retirada' ?>
        </td>
        <td>
            <select
                onchange="atualizarStatus(this.value, <?= $pedido['id'] ?>, '<?= (isset($pedido['tipo']) && ($pedido['tipo'] === '1-Entrega' || strtolower($pedido['tipo']) === 'entrega')) ? 'entrega' : 'retirada' ?>')"
                class="status-select <?= $pedido['status'] === 'Pronto' ? 'status-select-pronto' : ($pedido['status'] === 'Entregue' ? 'status-select-entregue' : '') ?>"
            >
                <option value="Pronto" <?= $pedido['status'] === 'Pronto' ? 'selected' : '' ?>>Pronto</option>
                <option value="Entregue" <?= $pedido['status'] === 'Entregue' ? 'selected' : '' ?>>Entregue</option>
            </select>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<a href="/florV3/public/index.php?rota=painel" class="voltar-simples">← Voltar</a>

<style>
.voltar-simples {
    display: inline-block;
    padding: 8px 20px;
    background-color: #111;
    color: #fff;
    font-size: 14px;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s;
}
.voltar-simples:hover {
    background-color: #333;
}
</style>

<script>
function atualizarStatus(novoStatus, id, tipo) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('tipo', tipo);
    formData.append('status', novoStatus);

    if (novoStatus === 'Entregue') {
        const confirmaMensagem = confirm("Deseja registrar uma mensagem para este pedido? (Ela ficará salva no histórico)");
        if (confirmaMensagem) {
            const mensagem = prompt("Digite a mensagem que deseja registrar:");
            if (mensagem !== null && mensagem.trim() !== "") {
                formData.append('mensagem', mensagem.trim());
            }
        }
    }

    fetch('/florV3/public/index.php?rota=atualizar-status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
.then(result => {
    if (result === 'OK') {
        const selectElement = document.querySelector(`select[onchange*="atualizarStatus(this.value, ${id},"]`);
        selectElement.classList.remove('status-select-pronto', 'status-select-entregue');
        if (novoStatus === 'Pronto') {
            selectElement.classList.add('status-select-pronto');
        } else if (novoStatus === 'Entregue') {
            selectElement.classList.add('status-select-entregue');
        }

        // Forçar atualização visual
        selectElement.style.display = 'none';
        setTimeout(() => {
            selectElement.style.display = 'inline-block';
        }, 10);

    } else {
        alert('Erro ao atualizar status.');
    }
});

}
</script>

</body>
</html>
