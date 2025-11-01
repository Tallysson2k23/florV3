<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Escolher Tipo de Pedido - Flor de Cheiro</title>
  <style>
    :root{
      /* altura do topo e espaço extra abaixo dele */
      --topbar-h: 90px;
      --extra-gap: 200px; /* ↑ aumente/diminua para mover o bloco mais pra baixo */
    }

    * { box-sizing: border-box; }

    body{
      margin:0;
      padding:0;
      font-family:'Segoe UI',sans-serif;
      background:#f3f4f6;
      color:#111;
      min-height:100vh;
    }

    /* Topo fixo (logo central + botão voltar à esquerda) */
    .top-bar{
      position:fixed;
      inset:0 0 auto 0;            /* top:0; left:0; right:0 */
      height:var(--topbar-h);
      background:#111;
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      box-shadow:0 2px 6px rgba(0,0,0,.18);
      z-index:999;
    }
    .logo-img{ height:50px; object-fit:contain; }

    .btn-top-voltar{
      position:absolute;
      left:12px;
      top:50%;
      transform:translateY(-50%);
      display:inline-flex;
      align-items:center;
      gap:8px;
      background:#1f2937;
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
    .btn-top-voltar:hover{ background:#374151; }
    .btn-top-voltar:active{ transform:translateY(-50%) scale(.98); }

    /* Conteúdo posicionado mais abaixo */
    .content{
      max-width:560px;
      margin:0 auto;
      padding:calc(var(--topbar-h) + var(--extra-gap)) 20px 40px;
      text-align:center;
    }
    .content h3{
      margin:0 0 32px;
      font-size:22px;
      color:#111;
    }
    .content a{ display:block; margin-bottom:20px; text-decoration:none; }
    .content button{
      width:240px;
      padding:12px;
      font-size:16px;
      border:none;
      background:#111;
      color:#fff;
      border-radius:10px;
      cursor:pointer;
      transition:background .3s ease;
    }
    .content button:hover{ background:#333; }
  </style>
</head>
<body>

  <div class="top-bar">
    <a href="javascript:void(0)" class="btn-top-voltar" onclick="voltar()" aria-label="Voltar">← Voltar</a>
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
  </div>

  <div class="content">
    <h3>Escolha o tipo de pedido</h3>

    <a href="/florV3/public/index.php?rota=cadastrar-entrega">
      <button>Entrega</button>
    </a>

    <a href="/florV3/public/index.php?rota=cadastrar-retirada">
      <button>Retirada</button>
    </a>

    <!-- <a href="/florV3/public/index.php?rota=painel">
      <button>⬅ Voltar ao Painel</button>
    </a> -->
  </div>

  <script>
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
