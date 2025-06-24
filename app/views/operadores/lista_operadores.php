<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Operadores</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; padding: 50px; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #111; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        th { background: #eee; }
        a { text-decoration: none; color: #111; }
        button { background: #111; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Operadores</h2>
        <table>
            <tr><th>ID</th><th>Nome</th></tr>
            <?php foreach ($operadores as $op): ?>
                <tr><td><?= $op['id'] ?></td><td><?= htmlspecialchars($op['nome']) ?></td></tr>
            <?php endforeach; ?>
        </table>
        <a href="/florV3/public/index.php?rota=cadastrar-operador"><button>Novo Operador</button></a>
    </div>
</body>
</html>
