<form id="form-entrega" method="post" action="/florV3/public/index.php?rota=salvar-entrega" onsubmit="return confirmarEnvio()">
    <label>Nº Pedido: <input name="numero_pedido" required></label><br>
    <label>Tipo: <input name="tipo" value="1-Entrega" readonly></label><br>
    <label>Remetente: <input name="remetente" required></label>
    <label>Telefone: <input name="telefone_remetente"></label><br>
    <label>Destinatário: <input name="destinatario" required></label>
    <label>Telefone: <input name="telefone_destinatario"></label><br>
    <label>Endereço: <input name="endereco"></label>
    <label>Nº: <input name="numero_endereco"></label><br>
    <label>Bairro: <input name="bairro"></label>
    <label>Referência: <input name="referencia"></label><br>
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
