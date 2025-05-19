<h2>🔐 Login</h2>
<?php if (isset($_GET['erro'])): ?>
    <p style="color: red;">Login ou senha inválidos</p>
<?php endif; ?>

<form method="post" action="/florV3/public/index.php?rota=autenticar">
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Senha: <input type="password" name="senha" required></label><br>
    <button type="submit">Entrar</button>
</form>
