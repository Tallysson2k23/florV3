<h2>Lista de Vendedores</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <tr><th>Nome</th><th>Telefone</th></tr>
    <?php foreach ($vendedores as $v): ?>
    <tr>
        <td><?= htmlspecialchars($v['nome']) ?></td>
        <td><?= htmlspecialchars($v['telefone']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
