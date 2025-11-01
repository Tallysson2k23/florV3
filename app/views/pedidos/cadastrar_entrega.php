<?php
require_once __DIR__ . '/../../models/Produto.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Configuracao.php';

$pdo = Database::conectar();
$produtoModel = new Produto($pdo);
$produtos = $produtoModel->listarAtivos();

require_once __DIR__ . '/../../models/Vendedor.php';
$vendedorModel = new Vendedor($pdo);
$vendedores = $vendedorModel->listarAtivos();

$configModel = new \app\models\Configuracao($pdo);
$numeroPedidoPadrao = $configModel->obter('numero_pedido_padrao') ?? 'L20';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Entrega - Flor de Cheiro</title>

  <style>
    :root{
      --topbar-h: 90px;     /* altura da barra superior */
      --extra-gap: 80px;    /* espaço extra abaixo do topo (ajuste à vontade) */
    }
    *{ box-sizing:border-box; }

    body{
      font-family:'Segoe UI',sans-serif;
      background:#f3f4f6;
      margin:0;
      color:#111;
      min-height:100vh;
    }

    /* Topo fixo (logo central + botão voltar à esquerda) */
    .top-bar{
      position:fixed;
      inset:0 0 auto 0;
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

    /* Wrapper do formulário deslocado para baixo do topo fixo */
    .form-wrapper{
      max-width: 900px;
      margin: 0 auto;
      padding: calc(var(--topbar-h) + var(--extra-gap)) 24px 40px;
      background:#f3f4f6; /* mesmo fundo do body */
    }

    h2{
      text-align:center;
      margin:0 0 28px;
      font-size:24px;
      color:#111;
    }

    .form-group{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:20px;
      margin-bottom:20px;
    }
    .form-group.full{ grid-template-columns:1fr; }

    label{
      font-weight:600; color:#333; margin-bottom:6px; display:block;
    }
    .obrig{ color:red; margin-left:2px; }

    input, select, textarea{
      width:100%;
      padding:10px;
      border:1px solid #ccc;
      border-radius:8px;
      font-size:15px;
      background:#fff;
    }

    table{
      width:100%;
      border-collapse:collapse;
      background:#fff;
      border-radius:10px;
      overflow:hidden;
      box-shadow:0 2px 8px rgba(0,0,0,.04);
    }
    table thead th{
      background:#f0f0f0;
      padding:10px;
      border:1px solid #ddd;
      text-align:left;
    }
    table td{
      padding:10px;
      border:1px solid #ddd;
      vertical-align:middle;
    }

    .actions{
      text-align:center;
      margin-top:30px;
    }
    .actions button{
      background:#111;
      color:#fff;
      border:none;
      padding:12px 24px;
      margin:0 10px;
      font-size:16px;
      border-radius:10px;
      cursor:pointer;
      transition:background .3s;
    }
    .actions button:hover{ background:#333; }

    @media (max-width: 720px){
      .form-group{ grid-template-columns:1fr; }
    }
  </style>

  <link rel="stylesheet" href="/florV3/public/assets/css/choices.min.css">
</head>
<body>

  <div class="top-bar">
    <a href="javascript:void(0)" class="btn-top-voltar" onclick="voltar()" aria-label="Voltar">← Voltar</a>
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
  </div>

  <div class="form-wrapper">
    <h2>Cadastro de Entrega</h2>

    <form id="form-entrega" method="post" action="/florV3/public/index.php?rota=salvar-entrega">

      <div class="form-group">
        <div>
          <label>Nº Pedido:<span class="obrig">*</span></label>
          <input
            name="numero_pedido"
            id="numero_pedido"
            required
            value="<?= htmlspecialchars($numeroPedidoPadrao) ?>"
            pattern="^<?= htmlspecialchars(preg_quote($numeroPedidoPadrao, '/')) ?>.{5,}$"
            title="Digite pelo menos 5 caracteres após o prefixo <?= htmlspecialchars($numeroPedidoPadrao) ?>."
          >
        </div>
        <div>
          <label>Tipo:<span class="obrig">*</span></label>
          <input name="tipo" value="1-Entrega" readonly>
        </div>
      </div>

      <div class="form-group">
        <div>
          <label>Remetente:<span class="obrig">*</span></label>
          <input name="remetente" required>
        </div>
        <div>
          <label>Telefone do Remetente:<span class="obrig">*</span></label>
          <input name="telefone_remetente" required>
        </div>
      </div>

      <div class="form-group">
        <div>
          <label>Destinatário:</label>
          <input name="destinatario">
        </div>
        <div>
          <label>Telefone do Destinatário:</label>
          <input name="telefone_destinatario">
        </div>
      </div>

      <div class="form-group">
        <div>
          <label>Endereço:<span class="obrig">*</span></label>
          <input name="endereco" required>
        </div>
        <div>
          <label>Nº:<span class="obrig">*</span></label>
          <input name="numero_endereco" required>
        </div>
      </div>

      <div class="form-group">
        <div>
          <label>Bairro:<span class="obrig">*</span></label>
          <input name="bairro" required>
        </div>
        <div>
          <label>Referência:</label>
          <input name="referencia">
        </div>
      </div>

      <div class="form-group full">
        <label>Produto:<span class="obrig">*</span></label>
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
      <br>

      <div class="form-group full">
        <div>
          <label>Adicionais:</label>
          <textarea name="adicionais" rows="3" placeholder="Observações extras ou detalhes adicionais..."></textarea>
        </div>
      </div>

      <div class="form-group full">
        <div>
          <label>Data de Abertura:<span class="obrig">*</span></label>
          <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>" required>
        </div>
      </div>

      <div class="form-group full">
        <div>
          <label>Vendedor:<span class="obrig">*</span></label>
          <select name="vendedor_codigo" required>
            <option value="">Selecione</option>
            <?php foreach ($vendedores as $v): ?>
              <option value="<?= htmlspecialchars($v['codigo']) ?>">
                <?= htmlspecialchars($v['codigo']) ?> - <?= htmlspecialchars($v['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <label><strong>Enviar para: <span class="obrig">*</span></strong></label><br>
      <div style="margin-left:20px;">
        <input type="radio" name="enviar_para" id="producao" value="producao" required>
        <label for="producao">Mandar para Produção</label><br>

        <input type="radio" name="enviar_para" id="pronta_entrega" value="pronta_entrega" required>
        <label for="pronta_entrega">Pronta Entrega</label>
      </div>
      <br>

      <input type="hidden" name="imprimir" id="imprimir" value="0">

      <div class="actions">
        <button type="button" onclick="confirmarEnvioEntrega()">Enviar</button>
        <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
      </div>
    </form>
  </div>

  <script>
    // Botão voltar: histórico, senão vai ao painel
    function voltar(){
      try{
        if (document.referrer && window.history.length > 1){
          window.history.back();
          return;
        }
      }catch(e){}
      window.location.href = '/florV3/public/index.php?rota=painel';
    }

    function camposMinimosOk() {
      const form = document.getElementById('form-entrega');
      if (!form.reportValidity()) return false;
      if (!document.querySelector('#lista-produtos tr')) {
        alert('Adicione pelo menos um produto.');
        return false;
      }
      return true;
    }

    function confirmarEnvioEntrega() {
      if (!camposMinimosOk()) return;

      const enviarParaSelecionado = document.querySelector('input[name="enviar_para"]:checked');
      if (!enviarParaSelecionado) {
        alert("Por favor, selecione uma opção em 'Enviar para'.");
        return;
      }

      if (!confirm("Deseja realmente enviar o pedido?")) return;
      document.getElementById("imprimir").value = confirm("Deseja imprimir o cupom?") ? "1" : "0";
      document.getElementById("form-entrega").submit();
    }

    function confirmarCancelamento() {
      if (confirm("Deseja realmente cancelar? Os dados serão perdidos.")) {
        window.location.href = "/florV3/public/index.php?rota=painel";
      }
    }
  </script>

  <script>
    // Nº pedido com prefixo fixo + mínimo 5 caracteres após o prefixo
    document.addEventListener("DOMContentLoaded", function () {
      const campo = document.getElementById("numero_pedido");
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

  <script src="/florV3/public/assets/js/choices.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const seletor = document.getElementById('produto-seletor');
      const lista   = document.getElementById('lista-produtos');

      const choices = new Choices(seletor, {
        searchEnabled: true,
        placeholder: true,
        searchResultLimit: 5,
        renderChoiceLimit: 5,
        itemSelectText: '',
        shouldSort: false
      });

      seletor.addEventListener('change', function () {
        const nome = seletor.value;
        if (!nome) return;

        const idUnico = Date.now() + Math.floor(Math.random()*1000);

        const linha = document.createElement('tr');
        linha.innerHTML = `
          <td>
            <input type="hidden" name="produtos[${idUnico}][nome]" value="${nome}">
            ${nome}
          </td>
          <td><input type="number" name="produtos[${idUnico}][quantidade]" value="1" min="1" required style="width:70px;"></td>
          <td><input type="text" name="produtos[${idUnico}][observacao]" placeholder="Observação..."></td>
          <td><button type="button" onclick="this.closest('tr').remove()">❌</button></td>
        `;
        lista.appendChild(linha);

        // Reseta a seleção no Choices
        choices.removeActiveItems();
        choices.setChoiceByValue(''); // limpa selecionado
      });
    });
  </script>

</body>
</html>
