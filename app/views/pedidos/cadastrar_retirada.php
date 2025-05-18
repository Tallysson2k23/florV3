<form id="form-retirada" method="post" action="/florV3/public/index.php?rota=salvar-retirada" onsubmit="return confirmarEnvio()">
    <label>Nº Pedido: <input name="numero_pedido" required></label><br>
    <label>Tipo: <input name="tipo" value="2-Retirada" readonly></label><br>
    <label>Nome: <input name="nome" required></label>
    <label>Telefone: <input name="telefone"></label><br>
    <label>Produtos: <input name="produtos"></label><br>
    <label>Adicionais: <input name="adicionais"></label><br>
    <label>Data: <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>"></label><br>

    <button type="submit">Enviar</button>
    <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
</form>

<script>
function confirmarEnvio() {
    return confirm("Deseja realmente enviar o pedido?");
}

function confirmarCancelamento() {
    if (confirm("Deseja realmente cancelar? Os dados serão perdidos.")) {
        window.location.href = "/florV3/public/index.php?rota=painel";
    }
}
</script>
