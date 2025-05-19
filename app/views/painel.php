<!-- app/views/painel.php -->

<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}

$impressao = $_SESSION['impressao_pendente'] ?? null;
unset($_SESSION['impressao_pendente']);

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}

// 👇 captura e limpa a variável de impressão pendente
$impressao = $_SESSION['impressao_pendente'] ?? null;
unset($_SESSION['impressao_pendente']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel FlorV3</title>
</head>

<body>
<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
<div id="notificacao-sucesso" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #1a1f24;
    color: #b9fbc0;
    border-left: 5px solid #00ff88;
    padding: 15px 20px;
    border-radius: 6px;
    font-family: Arial;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    z-index: 9999;
">
    ✅ Pedido feito com sucesso!
</div>

<script>
// Oculta após 5 segundos
setTimeout(() => {
    const notif = document.getElementById('notificacao-sucesso');
    if (notif) notif.style.display = 'none';
}, 1000);

// Remove o parâmetro ?sucesso=1 da URL sem recarregar
if (window.history.replaceState) {
    const url = new URL(window.location);
    url.searchParams.delete('sucesso');
    window.history.replaceState({}, document.title, url.pathname);
}
</script>
<?php endif; ?>

<!-- 👇 Abre a aba de impressão se houve confirmação -->
<?php if ($impressao): ?>
<script>
    window.onload = () => {
        window.open(
            '/florV3/public/index.php?rota=imprimir-pedido&id=<?= $impressao['id'] ?>&tipo=<?= $impressao['tipo'] ?>',
            '_blank'
        );
    };
</script>
<?php endif; ?>

<h1>Painel Principal</h1>
<h2>Bem-vindo ao sistema FlorV3</h2>
<p>O que deseja cadastrar?</p>

<a href="/florV3/public/index.php?rota=escolher-tipo">
    <button>Cadastrar Pedido</button>
</a>
<a href="/florV3/public/index.php?rota=historico">
    <button>Ver Histórico</button>
</a>
<a href="/florV3/public/index.php?rota=acompanhamento">
    <button>📦 Acompanhar Pedidos</button>
</a>

<?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin'): ?>
    <a href="/florV3/public/index.php?rota=usuarios">
        <button>👤 Gerenciar Usuários</button>
    </a>
<?php endif; ?>

<a href="/florV3/public/index.php?rota=logout">
    <button style="background: red; color: white;">🚪 Sair</button>
</a>

<?php if ($impressao): ?>
<script>
    window.onload = () => {
        const id = <?= json_encode($impressao['id']) ?>;
        const tipo = <?= json_encode($impressao['tipo']) ?>;

        // Abre a aba de impressão
        window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${id}&tipo=${tipo}`, '_blank');
    };
</script>
<?php endif; ?>

</body>
</html>
