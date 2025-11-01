



<h2>Cadastrar Pedido</h2>

<form action="index.php?rota=salvar-pedido" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" required>

    <label for="tipo">Tipo:</label>
    <select name="tipo" id="tipo" required>
        <option value="Retirada">Retirada</option>
        <option value="Entrega">Entrega</option>
    </select>

    <label for="numero_pedido">Nº Pedido:</label>
    <input type="text" name="numero_pedido" id="numero_pedido" required>

    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade" min="1" required>

    <label for="produto">Produto:</label>
    <select name="produto" id="produto" required>
        <option value="Flor 1">Flor 1</option>
        <option value="Flor 2">Flor 2</option>
        <option value="Flor 3">Flor 3</option>
        <option value="Flor 4">Flor 4</option>
        <option value="Flor 5">Flor 5</option>
    </select>

    <label for="complemento">Complemento:</label>
    <input type="text" name="complemento" id="complemento">

    <label for="observacao">Observação:</label>
    <textarea name="observacao" id="observacao"></textarea>

    <label for="data">Data:</label>
    <input type="date" name="data" id="data" required>

    <button type="submit">Enviar Pedido</button>
    <a href="index.php?rota=produtos"><button type="button">Cancelar</button></a>
</form>
