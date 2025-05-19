<form id="form-retirada" method="post" action="/florV3/public/index.php?rota=salvar-retirada">
    <label>Nº Pedido: <input name="numero_pedido" required></label><br>
    <label>Tipo: <input name="tipo" value="2-Retirada" readonly></label><br>
    <label>Nome: <input name="nome" required></label>
    <label>Telefone: <input name="telefone"></label><br>
    <label>Produtos: <input name="produtos"></label><br>
    <label>Adicionais: <input name="adicionais"></label><br>
    <label>Data: <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>"></label><br>

    <input type="hidden" name="imprimir" id="imprimir-retirada" value="0">

    <button type="button" onclick="confirmarEnvioRetirada()">Enviar</button>
    <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
</form>

<script>
function confirmarEnvioRetirada() {
    if (confirm("Deseja realmente enviar o pedido?")) {
        const imprimir = confirm("Deseja imprimir o cupom?");
        document.getElementById("imprimir-retirada").value = imprimir ? "1" : "0";
        document.getElementById("form-retirada").submit();
    }
}

function confirmarCancelamento() {
    if (confirm("Deseja realmente cancelar? Os dados serão perdidos.")) {
        window.location.href = "/florV3/public/index.php?rota=painel";
    }
}
</script>
