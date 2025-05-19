<h2>🔎 Histórico de Pedidos</h2>

<form method="get" action="/florV3/public/index.php">
    <input type="hidden" name="rota" value="historico">
    <input type="text" id="campo-busca" name="busca" placeholder="Buscar por nome ou número do pedido" required>
    <button type="submit">Pesquisar</button>
</form>

<!-- Sugestões ao digitar -->
<ul id="sugestoes" style="list-style:none; padding:0; margin:10px 0;"></ul>

<a href="/florV3/public/index.php?rota=painel">
    <button style="margin-bottom: 20px;">⬅ Voltar ao Painel</button>
</a>

<?php if (isset($resultados) && count($resultados) > 0): ?>

    <?php if (count($resultados) > 5): ?>
        <button id="mostrar-todos">Mostrar todos os pedidos</button>
    <?php endif; ?>

    <table border="1" cellpadding="5" cellspacing="0" style="margin-top:20px;">
        <tr>
            <th>Tipo</th>
            <th>Nº Pedido</th>
            <th>Nome / Remetente</th>
        </tr>

        <?php foreach ($resultados as $pedido): ?>

            <tr class="pedido-linha <?= $index >= 5 ? 'oculto' : '' ?>">
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

<!-- CSS -->
<style>
    .oculto { display: none; }

    #sugestoes li {
        background: #f1f1f1;
        margin-bottom: 2px;
        padding: 5px;
        border: 1px solid #ccc;
    }

    #sugestoes li:hover {
        background: #ddd;
    }
</style>

<!-- JS: Mostrar todos os pedidos -->
<!-- PAGINAÇÃO -->
<?php if ($total > $porPagina): ?>
<div style="margin-top: 15px; display: flex; align-items: center; gap: 10px;">
    <?php if ($pagina > 1): ?>
        <a href="?rota=historico&pagina=1<?= $busca ? '&busca=' . urlencode($busca) : '' ?>">⏮️</a>
        <a href="?rota=historico&pagina=<?= $pagina - 1 ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">◀️</a>
    <?php endif; ?>

    <span><?= $inicio + 1 ?> – <?= min($inicio + $porPagina, $total) ?> / <?= $total ?></span>

    <?php if ($pagina < $totalPaginas): ?>
        <a href="?rota=historico&pagina=<?= $pagina + 1 ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">▶️</a>
        <a href="?rota=historico&pagina=<?= $totalPaginas ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">⏭️</a>
    <?php endif; ?>
</div>
<?php endif; ?>


<!-- JS: Sugestões automáticas -->
<script>
const campoBusca = document.getElementById('campo-busca');
const sugestoes = document.getElementById('sugestoes');

// Lista de sugestões (nome + número)
const dados = <?= json_encode(array_map(fn($p) => $p['nome'] . ' - ' . $p['numero_pedido'], $todos)) ?>;

campoBusca.addEventListener('input', function () {
    const valor = this.value.toLowerCase();
    sugestoes.innerHTML = '';

    if (valor.length === 0) return;

    const filtrados = dados.filter(item => item.toLowerCase().includes(valor)).slice(0, 5);
    filtrados.forEach(item => {
        const li = document.createElement('li');
        li.textContent = item;
        li.style.cursor = 'pointer';
        li.onclick = () => {
            campoBusca.value = item; // ✅ pega nome + número juntos
            sugestoes.innerHTML = '';
        };
        sugestoes.appendChild(li);
    });
});


</script>
