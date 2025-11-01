<?php
require_once __DIR__ . '/../../models/Produto.php';
require_once __DIR__ . '/../../models/Vendedor.php';
require_once __DIR__ . '/../../models/Configuracao.php';
require_once __DIR__ . '/../../../config/database.php';

$pdo = Database::conectar();
$produtoModel  = new Produto($pdo);
$produtos      = $produtoModel->listarAtivos();

$vendedorModel = new Vendedor($pdo);
$vendedores    = $vendedorModel->listarAtivos();

$configModel = new \app\models\Configuracao($pdo);
$numeroPedidoPadrao = $configModel->obter('numero_pedido_padrao') ?? 'L20';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Retirada - Flor de Cheiro</title>
  <style>
    :root{ --topbar-h:90px; --extra-gap:80px; }
    *{ box-sizing:border-box; }
    body{ font-family:'Segoe UI',sans-serif; background:#f3f4f6; margin:0; color:#111; min-height:100vh; }

    /* Topo fixo */
    .top-bar{
      position:fixed; inset:0 0 auto 0; height:var(--topbar-h);
      background:#111; color:#fff; display:flex; align-items:center; justify-content:center;
      box-shadow:0 2px 6px rgba(0,0,0,.18); z-index:999;
    }
    .logo-img{ height:50px; object-fit:contain; }

    .btn-top-voltar{
      position:absolute; left:12px; top:50%; transform:translateY(-50%);
      display:inline-flex; align-items:center; gap:8px;
      background:#1f2937; color:#fff !important; border:1px solid rgba(255,255,255,.18);
      padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; text-decoration:none; cursor:pointer;
      transition:background .2s ease, transform .05s ease;
    }
    .btn-top-voltar:hover{ background:#374151; }
    .btn-top-voltar:active{ transform:translateY(-50%) scale(.98); }

    /* Conteúdo */
    .form-wrapper{ max-width:900px; margin:0 auto; padding:calc(var(--topbar-h) + var(--extra-gap)) 24px 40px; }
    h2{ text-align:center; margin:0 0 28px; font-size:24px; color:#111; }

    .form-group{ display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
    .form-group.full{ grid-template-columns:1fr; }
    label{ font-weight:600; color:#333; margin-bottom:6px; display:block; }

    input,select,textarea{ width:100%; padding:10px; border:1px solid #ccc; border-radius:8px; font-size:15px; background:#fff; }
    textarea{ min-height:70px; resize:vertical; }

    table{ width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.04); }
    table thead th{ background:#f0f0f0; padding:10px; border:1px solid #ddd; text-align:left; }
    table td{ padding:10px; border:1px solid #ddd; vertical-align:middle; }

    .actions{ text-align:center; margin-top:30px; }
    .actions button{ background:#111; color:#fff; border:none; padding:12px 24px; margin:0 10px; font-size:16px; border-radius:10px; cursor:pointer; transition:background .3s; }
    .actions button:hover{ background:#333; }

    @media (max-width:720px){ .form-group{ grid-template-columns:1fr; } }
  </style>

  <!-- Choices -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
  <script defer src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
</head>
<body>

  <div class="top-bar">
    <!-- href real para fallback; JS só intercepta para usar history.back() -->
    <a href="/florV3/public/index.php?rota=painel" class="btn-top-voltar" id="btnVoltarTopo" aria-label="Voltar">← Voltar</a>
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
  </div>

  <div class="form-wrapper">
    <h2>Cadastro de Retirada</h2>

    <form id="form-retirada" method="post" action="/florV3/public/index.php?rota=salvar-retirada">
      <div class="form-group">
        <div>
          <label>Nº Pedido: <span style="color:red">*</span></label>
          <input
            name="numero_pedido" id="numero_pedido" required
            value="<?= htmlspecialchars($numeroPedidoPadrao) ?>"
            pattern="^<?= htmlspecialchars(preg_quote($numeroPedidoPadrao, '/')) ?>.{5,}$"
            title="Digite pelo menos 5 caracteres após o prefixo <?= htmlspecialchars($numeroPedidoPadrao) ?>."
          >
        </div>
        <div>
          <label>Tipo:</label>
          <input name="tipo" value="2-Retirada" readonly>
        </div>
      </div>

      <div class="form-group">
        <div>
          <label>Nome: <span style="color:red">*</span></label>
          <input name="nome" required>
        </div>
        <div>
          <label>Telefone:</label>
          <input name="telefone">
        </div>
      </div>

      <div class="form-group full">
        <label>Adicionar Produto: <span style="color:red">*</span></label>
        <select id="produto-seletor">
          <option value="">Selecione...</option>
          <?php foreach ($produtos as $produto): ?>
            <option value="<?= htmlspecialchars($produto['codigo']) ?> - <?= htmlspecialchars($produto['nome']) ?>">
              <?= htmlspecialchars($produto['codigo']) ?> - <?= htmlspecialchars($produto['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <table id="tabela-produtos">
        <thead>
          <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Observação</th>
            <th>Remover</th>
          </tr>
        </thead>
        <tbody id="lista-produtos"></tbody>
      </table>

      <div class="form-group full">
        <div>
          <label>Adicionais:</label>
          <textarea name="adicionais" rows="3" placeholder="Observações extras ou detalhes adicionais..."></textarea>
        </div>
      </div>

      <div class="form-group full">
        <div>
          <label>Data de Abertura: <span style="color:red">*</span></label>
          <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>" required>
        </div>
      </div>

      <div class="form-group full">
        <label>Vendedor: <span style="color:red">*</span></label>
        <select name="vendedor_codigo" required>
          <option value="">Selecione</option>
          <?php foreach ($vendedores as $v): ?>
            <option value="<?= htmlspecialchars($v['codigo']) ?>">
              <?= htmlspecialchars($v['codigo']) ?> - <?= htmlspecialchars($v['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <label><strong>Enviar para: <span style="color:red;">*</span></strong></label><br>
      <div style="margin-left:20px;">
        <input type="radio" name="enviar_para" id="producao" value="producao" required>
        <label for="producao">Mandar para Produção</label><br>
        <input type="radio" name="enviar_para" id="pronta_entrega" value="pronta_entrega" required>
        <label for="pronta_entrega">Pronta Entrega</label>
      </div>
      <br>

      <input type="hidden" name="imprimir" id="imprimir-retirada" value="0">

      <div class="actions">
        <button type="button" onclick="confirmarEnvioRetirada()">Enviar</button>
        <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
      </div>
    </form>
  </div>

  <script>
    // Intercepta o clique no "← Voltar": se houver histórico de mesma origem, usa history.back();
    // caso contrário, segue o href para o painel (fallback).
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('btnVoltarTopo');
      if (!btn) return;
      btn.addEventListener('click', (e) => {
        try{
          const ref = document.referrer;
          const sameOrigin = ref && new URL(ref).origin === location.origin;
          if (sameOrigin && history.length > 1) {
            e.preventDefault();
            history.back();
          }
        }catch(_){}
      });
    });

    function camposMinimosRetiradaOk() {
      const form = document.getElementById('form-retirada');
      if (!form.reportValidity()) return false;
      if (!document.querySelector('#lista-produtos tr')) {
        alert('Adicione pelo menos um produto.');
        return false;
      }
      return true;
    }

    function confirmarEnvioRetirada() {
      if (!camposMinimosRetiradaOk()) return;
      if (confirm("Deseja realmente enviar o pedido?")) {
        document.getElementById("imprimir-retirada").value =
          confirm("Deseja imprimir o cupom?") ? "1" : "0";
        document.getElementById("form-retirada").submit();
      }
    }

    function confirmarCancelamento() {
      if (confirm("Deseja realmente cancelar? Os dados serão perdidos.")) {
        window.location.href = '/florV3/public/index.php?rota=painel';
      }
    }
  </script>

  <script>
    // Nº do pedido: mantém prefixo e exige 5+ caracteres após o prefixo
    document.addEventListener("DOMContentLoaded", function () {
      const campo   = document.getElementById("numero_pedido");
      const prefixo = "<?= $numeroPedidoPadrao ?>";

      if (!campo.value.startsWith(prefixo)) campo.value = prefixo;

      campo.addEventListener("keydown", function (e) {
        const pos = campo.selectionStart;
        if ((pos <= prefixo.length) && (e.key === "Backspace" || e.key === "Delete")) e.preventDefault();
        if (pos < prefixo.length && !["ArrowLeft","ArrowRight","Tab"].includes(e.key)) {
          e.preventDefault();
          campo.setSelectionRange(campo.value.length, campo.value.length);
        }
      });

      campo.addEventListener("focus", function () {
        setTimeout(() => {
          if (campo.selectionStart < prefixo.length) {
            campo.setSelectionRange(campo.value.length, campo.value.length);
          }
        }, 0);
      });

      function validarNumeroPedido() {
        const resto = campo.value.slice(prefixo.length).trim();
        campo.setCustomValidity(resto.length < 5 ? `Digite pelo menos 5 caracteres após ${prefixo}.` : '');
      }
      campo.addEventListener('input', validarNumeroPedido);
      validarNumeroPedido();
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const seletor = document.getElementById('produto-seletor');
      const lista   = document.getElementById('lista-produtos');

      const choices = new Choices(seletor, {
        searchEnabled:true, placeholder:true,
        searchResultLimit:5, renderChoiceLimit:5,
        itemSelectText:'', shouldSort:false
      });

      seletor.addEventListener('change', function () {
        const valor = seletor.value;
        if (!valor) return;

        const [codigo, ...nomeArray] = valor.split(' - ');
        const nome = nomeArray.join(' - ').trim();
        const idUnico = Date.now() + Math.floor(Math.random()*1000);

        const linha = document.createElement('tr');
        linha.innerHTML = `
          <td>
            <input type="hidden" name="produtos[${idUnico}][codigo]" value="${codigo}">
            <input type="hidden" name="produtos[${idUnico}][nome]"   value="${nome}">
            ${codigo} - ${nome}
          </td>
          <td><input type="number" name="produtos[${idUnico}][quantidade]" value="1" min="1" required style="width:70px;"></td>
          <td><input type="text" name="produtos[${idUnico}][observacao]" placeholder="Observação..."></td>
          <td><button type="button" onclick="this.closest('tr').remove()">❌</button></td>
        `;
        lista.appendChild(linha);

        choices.removeActiveItems();
        choices.setChoiceByValue('');
      });
    });
  </script>

</body>
</html>
