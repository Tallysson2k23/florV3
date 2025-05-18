<!-- app/views/pedidos/cadastrar.php -->
<form method="post" action="/florV3/public/index.php?rota=salvar-pedido">
    <label>Nome: <input type="text" name="nome" required></label><br>
    <label>Tipo:
        <select name="tipo">
            <option value="1-Retirada">1-Retirada</option>
            <option value="2-Entrega">2-Entrega</option>
        </select>
    </label><br>
    <label>Nº Pedido: <input type="text" name="numero_pedido" required></label><br>
    
    <label>QNT: <input type="text" name="quantidade" required></label><br>
    <label>Produtos: <input type="text" name="produto" placeholder="Ex: Buquê tradicional"></label><br>
    <label>Complemento: <input type="text" name="complemento" placeholder="Ex: Adicionar 1 girassol"></label><br>
    
    <label>Obs.: <input type="text" name="observacao"></label><br>
    
    <label>Data: <input type="date" name="data" value="<?= date('Y-m-d') ?>"></label><br>
    
    <button type="submit" style="background:green;color:white;">Enviar</button>
    <button type="reset" style="background:red;color:white;">Cancelar</button>
</form>

