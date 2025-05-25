<h2>Lista de Produtos</h2>
<table border="1" cellpadding="8">
    <tr><th>Nome</th></tr>
    <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?= htmlspecialchars($produto['nome']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="/florV3/public/index.php?rota=painel">← Voltar ao Painel</a>
