<h2>📋 Detalhes do Pedido</h2>

<?php if ($dados): ?>
    <ul>
        <?php foreach ($dados as $campo => $valor): ?>
            <li><strong><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</strong> <?= htmlspecialchars($valor) ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="/florV3/public/index.php?rota=historico"><button>⬅ Voltar ao Histórico</button></a>
<?php else: ?>
    <p>Pedido não encontrado.</p>
<?php endif; ?>
