<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acompanhamento do Atendente - Flor de Cheiro</title>
<style>
    body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }
    h1 { color: #111; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table, th, td { border: 1px solid #ccc; }
    th, td { padding: 10px; text-align: left; }
    th { background: #eee; }

    .status-select {
        width: 100%;
        padding: 10px;
        font-weight: bold;
        color: white;
        border: none;
        border-radius: 5px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D'10'%20height%3D'6'%20viewBox%3D'0%200%2010%206'%20fill%3D'none'%20xmlns%3D'http%3A//www.w3.org/2000/svg'%3E%3Cpath%20d%3D'M1%201L5%205L9%201'%20stroke%3D'white'%20stroke-width%3D'2'%20stroke-linecap%3D'round'%20stroke-linejoin%3D'round'/%3E%3C/svg%3E");
    }

    .status-select-pronto {
        background-color: orange !important;
    }

    .status-select-entregue {
        background-color: green !important;
    }
</style>

</head>
<body>
    <form method="get" action="index.php">
    <input type="hidden" name="rota" value="acompanhamento-atendente">
    <label for="data">Selecionar Data:</label>
    <input type="date" name="data" id="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
</form>


<h1>Acompanhamento do Atendente (Pronto e Entregue)</h1>

<h2>Pedidos de Entrega</h2>
<table>
    <tr>
        <th>Nº Pedido</th>
        <th>Cliente</th>
        <th>Produto</th>
        <th>Status</th>
    </tr>
    <?php foreach ($entregas as $pedido): ?>
    <tr>
        <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
        <td><?= htmlspecialchars($pedido['destinatario']) ?></td>
        <td><?= htmlspecialchars($pedido['produtos']) ?></td>
        <td class="<?= $pedido['status'] === 'Pronto' ? 'status-td-pronto' : ($pedido['status'] === 'Entregue' ? 'status-td-entregue' : '') ?>">
    <select
    onchange="atualizarStatus(this.value, <?= $pedido['id'] ?>, '<?= $pedido['tipo'] === '1-Entrega' ? 'entrega' : 'retirada' ?>')"
    class="status-select <?= $pedido['status'] === 'Pronto' ? 'status-select-pronto' : ($pedido['status'] === 'Entregue' ? 'status-select-entregue' : '') ?>"
>
    <option value="Pronto" <?= $pedido['status'] === 'Pronto' ? 'selected' : '' ?>>Pronto</option>
    <option value="Entregue" <?= $pedido['status'] === 'Entregue' ? 'selected' : '' ?>>Entregue</option>
</select>

</td>



    </tr>
    <?php endforeach; ?>
</table>

<h2>Pedidos de Retirada</h2>
<table>
    <tr>
        <th>Nº Pedido</th>
        <th>Cliente</th>
        <th>Produto</th>
        <th>Status</th>
    </tr>
    <?php foreach ($retiradas as $pedido): ?>
    <tr>
        <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
        <td><?= htmlspecialchars($pedido['nome']) ?></td>
        <td><?= htmlspecialchars($pedido['produtos']) ?></td>
        <td class="<?= $pedido['status'] === 'Pronto' ? 'status-td-pronto' : ($pedido['status'] === 'Entregue' ? 'status-td-entregue' : '') ?>">
    <select
    onchange="atualizarStatus(this.value, <?= $pedido['id'] ?>, '<?= $pedido['tipo'] === '1-Entrega' ? 'entrega' : 'retirada' ?>')"
    class="status-select <?= $pedido['status'] === 'Pronto' ? 'status-select-pronto' : ($pedido['status'] === 'Entregue' ? 'status-select-entregue' : '') ?>"
>
    <option value="Pronto" <?= $pedido['status'] === 'Pronto' ? 'selected' : '' ?>>Pronto</option>
    <option value="Entregue" <?= $pedido['status'] === 'Entregue' ? 'selected' : '' ?>>Entregue</option>
</select>

</td>



    </tr>
    <?php endforeach; ?>
</table>
<script>
function atualizarStatus(novoStatus, id, tipo) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('tipo', tipo);
    formData.append('status', novoStatus);

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
    } else {
        alert('Erro ao atualizar status.');
    }
})


}
</script>




</body>
</html>
