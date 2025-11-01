<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Pedidos Cancelados</title>
  <style>
    :root{
      --preto:#111;
      --cinza:#1f2937;
      --cinza2:#374151;
    }
    body{
      font-family: Arial, sans-serif;
      background:#f3f4f6;
      margin:0;
      padding:0;
    }

    /* Topo igual à outra tela */
    .top-bar{
      width:100%;
      background:var(--preto);
      text-align:center;
      padding:15px 0;
      position:sticky;   /* fica colado no topo ao rolar */
      top:0;
      z-index:999;
      box-shadow:0 2px 6px rgba(0,0,0,.18);
    }
    .logo-img{
      height:52px;
      object-fit:contain;
    }
    .btn-top-voltar{
      position:absolute;
      left:12px;
      top:50%;
      transform:translateY(-50%);
      display:inline-flex;
      align-items:center;
      gap:8px;
      background:var(--cinza);
      color:#fff !important;
      border:1px solid rgba(255,255,255,.18);
      padding:8px 14px;
      border-radius:8px;
      font-size:13px;
      font-weight:700;
      text-decoration:none;
      cursor:pointer;
      transition:background .2s ease, transform .05s ease;
    }
    .btn-top-voltar:hover{ background:var(--cinza2); }
    .btn-top-voltar:active{ transform:translateY(-50%) scale(.98); }

    .container{
      padding:20px;
      max-width:900px;
      margin:0 auto;
    }

    h2{
      text-align:center;
      margin:20px 0;
    }

    table{
      width:100%;
      border-collapse:collapse;
      background:#fff;
    }
    th, td{
      padding:10px;
      border:1px solid #ddd;
      text-align:center;
    }
    th{ background:#eee; }

    .voltar{
      text-align:center;
      margin:24px 0 8px;
    }
    .btn-voltar-rodape{
      display:inline-flex;
      align-items:center;
      gap:8px;
      background:#555;
      color:#fff !important;
      border:none;
      padding:10px 16px;
      border-radius:8px;
      font-size:15px;
      cursor:pointer;
      text-decoration:none;
      transition:background .2s ease;
    }
    .btn-voltar-rodape:hover{ background:#222; }
  </style>
</head>
<body>

  <div class="top-bar">
    <a href="javascript:void(0)" class="btn-top-voltar" onclick="voltar()" aria-label="Voltar">
      ← Voltar
    </a>
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
  </div>

  <div class="container">
    <h2>Pedidos Cancelados</h2>

    <?php if (!empty($cancelados) && count($cancelados) > 0): ?>
      <table>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Tipo</th>
          <th>Data</th>
        </tr>
        <?php foreach ($cancelados as $pedido): ?>
          <tr>
            <td><?= htmlspecialchars($pedido['id']) ?></td>
            <td>
              <a href="/florV3/public/index.php?rota=detalhes&id=<?= urlencode($pedido['id']) ?>&tipo=<?= urlencode(strtolower($pedido['tipo'])) ?>">
                <?= htmlspecialchars($pedido['nome'] ?? $pedido['remetente'] ?? $pedido['destinatario'] ?? '') ?>
              </a>
            </td>
            <td><?= htmlspecialchars($pedido['tipo']) ?></td>
            <td><?= htmlspecialchars($pedido['data_abertura']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p style="text-align:center;">Nenhum pedido cancelado até o momento.</p>
    <?php endif; ?>

    <!-- botão opcional no rodapé -->
    <div class="voltar">
      <a class="btn-voltar-rodape" href="/florV3/public/index.php?rota=painel">← Voltar ao Painel</a>
    </div>
  </div>

  <script>
    // Mesmo comportamento da outra tela:
    // volta no histórico; se não tiver, redireciona para o painel.
    function voltar(){
      try{
        if (document.referrer && window.history.length > 1){
          window.history.back();
          return;
        }
      }catch(e){}
      window.location.href = '/florV3/public/index.php?rota=painel';
    }
  </script>

</body>
</html>
