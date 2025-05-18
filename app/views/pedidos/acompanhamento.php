<h2>📦 Acompanhamento de Pedidos</h2>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Tipo</th>
        <th>Nº Pedido</th>
        <th>Nome</th>
        <th>Status</th>
        <th>Data</th>
        <th>Hora</th>
    </tr>
    <?php foreach ($todosPedidos as $pedido): ?>
        <tr>
            <td><?= htmlspecialchars($pedido['tipo']) ?></td>
            <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
            <td><?= htmlspecialchars($pedido['nome']) ?></td>
            <td>
                <select onchange="atualizarStatus(<?= $pedido['id'] ?>, '<?= strtolower(substr($pedido['tipo'], 2)) ?>', this.value)">
                    <?php
                    $opcoes = ['Pendente', 'Produção', 'Pronto', 'Entregue'];
                    foreach ($opcoes as $opcao):
                        $selected = $pedido['status'] === $opcao ? 'selected' : '';
                        echo "<option value=\"$opcao\" $selected>$opcao</option>";
                    endforeach;
                    ?>
                </select>
            </td>
            <td><?= htmlspecialchars($pedido['data_abertura']) ?></td>
            <td><?= date('H:i', strtotime($pedido['hora'])) ?></td>

        </tr>
    <?php endforeach; ?>
</table>
<a href="/florV3/public/index.php?rota=painel">
    <button style="margin-bottom: 20px;">⬅ Voltar ao Painel</button>
</a>
<script>
function atualizarStatus(id, tipo, status) {
    fetch('/florV3/public/index.php?rota=atualizar-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&tipo=${tipo}&status=${encodeURIComponent(status)}`
    }).then(res => res.text()).then(data => {
        console.log('Status atualizado:', data);
    });
}
</script>
