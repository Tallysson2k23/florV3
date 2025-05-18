<h2>🔎 Histórico de Pedidos</h2>

<form method="get" action="/florV3/public/index.php">
    <input type="hidden" name="rota" value="historico">
    <input type="text" name="busca" placeholder="Buscar por nome ou número do pedido" required>
    <button type="submit">Pesquisar</button>
    
</form>
<a href="/florV3/public/index.php?rota=painel">
    <button style="margin-bottom: 20px;">⬅ Voltar ao Painel</button>
</a>

<?php if (isset($resultados) && count($resultados) > 0): ?>
    <table border="1" cellpadding="5" cellspacing="0" style="margin-top:20px;">
        <tr>
            <th>Tipo</th>
            <th>Nº Pedido</th>
            <th>Nome / Remetente</th>
        </tr>
        <?php foreach ($resultados as $pedido): ?>
            <tr>
                <td><?= htmlspecialchars($pedido['tipo']) ?></td>
                <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
                <td>
                    <a href="/florV3/public/index.php?rota=detalhes&id=<?= $pedido['id'] ?>&tipo=<?= strtolower(substr($pedido['tipo'], 2)) ?>">
                        <?= htmlspecialchars($pedido['nome']) ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif (isset($_GET['busca'])): ?>
    <p>Nenhum resultado encontrado.</p>
<?php endif; ?>
