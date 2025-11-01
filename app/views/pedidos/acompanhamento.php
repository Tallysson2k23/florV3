<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Acompanhamento - Flor de Cheiro</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; margin: 0; padding: 0; }

.top-bar {
  width: 100%;
  background-color: #111;
  color: white;
  font-family: inherit;
  font-size: 28px;
  text-align: center;
  padding: 15px 0;
  position: relative; /* <-- NOVO: para posicionar o botão dentro da barra */
}

/* Botão Voltar no topo (esquerda) */
.btn-top-voltar{
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #1f2937;
  color: #fff !important;
  border: 1px solid rgba(255,255,255,.18);
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  transition: background .2s ease, transform .05s ease;
}
.btn-top-voltar:hover{ background:#374151; }
.btn-top-voltar:active{ transform: translateY(-50%) scale(.98); }



    .notification-wrapper { position: absolute; top: 15px; right: 30px; cursor: pointer; z-index: 10000; }
    .notification-bell { font-size: 26px; color: white; position: relative; }
    .notification-badge { position: absolute; top: -6px; right: -10px; background: red; color: white; font-size: 12px; padding: 2px 6px; border-radius: 50%; }
    .notification-box { display: none; position: absolute; top: 35px; right: 0; background: white; color: black; width: 300px; max-height: 400px; overflow-y: auto; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); padding: 10px; }
    .notification-box h4 { margin: 0 0 10px; }
    .notification-item { padding: 5px 0; border-bottom: 1px solid #eee; font-size: 14px; }

    .container { max-width: 1200px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    h2 { text-align: center; color: #111; margin-bottom: 20px; }

    table { width: 100%; border-collapse: collapse; background: #fff; }
    th, td { padding: 12px; text-align: center; font-size: 14px; border: 1px solid #ddd; vertical-align: top; }
    th { background-color: #e4e4e4; font-weight: bold; }

    select { padding: 6px 10px; border-radius: 8px; font-size: 14px; border: 1px solid #ccc; font-weight: bold; color: white; cursor: pointer; }
    .status-pendente { background-color: rgb(231, 86, 60); }
    .status-producao { background-color: #f39c12; }
    .status-pronto   { background-color: #3498db; }

    button { padding: 6px 12px; background: #111; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; transition: background 0.3s; }
    button:hover { background-color: #333; }

    .btn-voltar { display: block; margin: 30px auto 0; background: #555; color: white; border: none; padding: 10px 20px; border-radius: 8px; text-align: center; font-size: 15px; cursor: pointer; transition: background 0.3s; }
    .btn-voltar:hover { background-color: #222; }

    select option { color: black; background-color: white; }

    .filtros { text-align: center; margin-bottom: 20px; }
    .filtros input[type="date"], .filtros input[type="text"] { padding: 8px; border-radius: 8px; border: 1px solid #ccc; font-size: 14px; }
    .filtros input[type="text"] { width: 300px; margin-right: 10px; }
    .filtros button { padding: 8px 16px; background-color: #111; color: white; border-radius: 8px; border: none; cursor: pointer; }

    .botoes-acoes { display: flex; gap: 8px; justify-content: center; }
    .logo-img { height: 52px; max-width: 100%; object-fit: contain; display: inline-block; }

    .codigo-badge{
      display:inline-flex; align-items:center; justify-content:center;
      min-width:72px; padding:4px 10px; border-radius:999px;
      font-weight:700; font-size:12px; line-height:1; color:#fff; letter-spacing:.3px;
      box-shadow:0 1px 0 rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.06);
    }
    .codigo-entrega  { background:#2ecc71; }
    .codigo-retirada { background:#e74c3c; }

    th.col-codigo, td.col-codigo { width: 120px; }
    td.col-codigo { padding: 8px; }

    .produtos-list { text-align: left; white-space: pre-wrap; line-height: 1.3; }
  </style>
</head>
<body>

<div class="top-bar">
  <a href="/florV3/public/index.php?rota=painel" class="btn-top-voltar" id="btnVoltarTopo" aria-label="Voltar">← Voltar</a>
  <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<audio id="notificacaoSom" preload="auto"></audio>
<button id="btnSom" style="
  position:fixed; right:14px; bottom:14px; z-index:99999;
  background:#111; color:#fff; border:none; border-radius:999px;
  padding:10px 14px; font-weight:600; cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,.15);
">🔊 Habilitar som</button>

<div class="container">
  <h2>📦 Acompanhamento de Pedidos</h2>

  <form method="GET" action="/florV3/public/index.php" class="filtros">
    <input type="hidden" name="rota" value="acompanhamento">
    <input type="date" name="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
    <input type="text" name="produto" placeholder="🔍 Buscar por produto" value="<?= htmlspecialchars($_GET['produto'] ?? '') ?>">
    <button type="submit">🔎 Filtrar</button>
  </form>

  <div class="notification-wrapper" onclick="toggleNotificationBox()">
    <div class="notification-bell">🔔
      <span class="notification-badge" id="notification-count" style="display:none;">0</span>
    </div>
    <div class="notification-box" id="notification-box">
      <h4>Pedidos Futuros</h4>
      <div id="notification-list"></div>
    </div>
  </div>

  <?php
  require_once __DIR__ . '/../../models/Operador.php';
  $operadorModel = new Operador();
  $operadores = $operadorModel->listarTodos();

  $todosPedidos = (isset($todosPedidos) && is_array($todosPedidos)) ? $todosPedidos : [];

  if (count($todosPedidos) > 1) {
    usort($todosPedidos, function($a, $b) {
      $ka = isset($a['ordem_fila']) ? (int)$a['ordem_fila']
        : (isset($a['data_abertura']) ? strtotime(trim(($a['data_abertura'] ?? '1970-01-01') . ' ' . ($a['hora'] ?? '00:00:00'))) : (int)($a['id'] ?? 0));
      $kb = isset($b['ordem_fila']) ? (int)$b['ordem_fila']
        : (isset($b['data_abertura']) ? strtotime(trim(($b['data_abertura'] ?? '1970-01-01') . ' ' . ($b['hora'] ?? '00:00:00'))) : (int)($b['id'] ?? 0));
      if ($ka === $kb) return ((int)($b['id'] ?? 0)) <=> ((int)($a['id'] ?? 0));
      return $kb <=> $ka;
    });
  }

  function normaliza_tipo_php(?string $tipo): string {
    $t = strtolower(trim((string)$tipo));
    if ($t === '') return '';
    $t = str_replace(['p_', 'p-'], '', $t);
    $pos = strpos($t, '-'); if ($pos !== false) $t = substr($t, $pos + 1);
    return preg_replace('/\s+/', '', $t);
  }

  // Extrai produtos de vários formatos/campos
  function produtos_html_php(array $pedido): string {
    $cands = ['produtos','produto','lista_produtos','itens','itens_pedido','descricao_produtos','detalhes_produtos','descricao'];
    $val = '';
    foreach ($cands as $k) {
      if (!empty($pedido[$k])) { $val = $pedido[$k]; break; }
    }
    $linhas = [];

    if (is_array($val)) {
      foreach ($val as $item) {
        if (is_array($item)) {
          $q = $item['qtd'] ?? $item['quantidade'] ?? $item['qtde'] ?? '';
          $n = $item['nome'] ?? $item['produto'] ?? $item['descricao'] ?? '';
          $s = trim(($q ? ($q.' x ') : '') . $n);
          if ($s !== '') $linhas[] = $s;
        } else {
          $s = trim((string)$item);
          if ($s !== '') $linhas[] = $s;
        }
      }
    } else {
      $s = trim((string)$val);
      if ($s !== '') {
        $s = preg_replace('/<br\s*\/?>/i', "\n", $s);
        if (($s[0] ?? '') === '[' || ($s[0] ?? '') === '{') {
          $arr = json_decode($s, true);
          if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($arr[0])) {
              foreach ($arr as $it) {
                if (is_array($it)) {
                  $q = $it['qtd'] ?? $it['quantidade'] ?? $it['qtde'] ?? '';
                  $n = $it['nome'] ?? $it['produto'] ?? $it['descricao'] ?? '';
                  $s2 = trim(($q ? ($q.' x ') : '') . $n);
                  if ($s2 !== '') $linhas[] = $s2;
                } elseif (!empty($it)) {
                  $linhas[] = trim((string)$it);
                }
              }
            } elseif (is_array($arr)) {
              $q = $arr['qtd'] ?? $arr['quantidade'] ?? $arr['qtde'] ?? '';
              $n = $arr['nome'] ?? $arr['produto'] ?? $arr['descricao'] ?? '';
              $s2 = trim(($q ? ($q.' x ') : '') . $n);
              if ($s2 !== '') $linhas[] = $s2;
            }
          } else {
            $linhas = preg_split('/\s*(?:,|;|\r?\n)\s*/', $s, -1, PREG_SPLIT_NO_EMPTY);
          }
        } else {
          $linhas = preg_split('/\s*(?:,|;|\r?\n)\s*/', $s, -1, PREG_SPLIT_NO_EMPTY);
        }
      }
    }
    return $linhas ? implode('<br>', array_map('htmlspecialchars', $linhas)) : '';
  }
  ?>

  <?php if (empty($todosPedidos)): ?>
    <p style="text-align: center; font-weight: bold; color: red;">Nenhum pedido pendente para hoje.</p>
  <?php endif; ?>

  <div id="lista-pedidos">
    <table>
      <tr>
        <th class="col-codigo">Código</th>
        <th>Cliente</th>
        <th>Status</th>
        <th>Produtos</th>
        <th>Ações</th>
      </tr>

      <?php foreach ($todosPedidos as $pedido):
        $statusRaw = $pedido['status'] ?? '';
        if (strtolower($statusRaw) === 'pronto') continue;

        $statusClasse = '';
        switch (strtolower($statusRaw)) {
          case 'pendente':  $statusClasse = 'status-pendente'; break;
          case 'produção':  $statusClasse = 'status-producao'; break;
          case 'pronto':    $statusClasse = 'status-pronto';   break;
        }

        $id   = (int)($pedido['id'] ?? 0);
        $nome = '';
        if (!empty($pedido['nome']))               $nome = htmlspecialchars($pedido['nome']);
        elseif (!empty($pedido['remetente']))      $nome = htmlspecialchars($pedido['remetente']);
        elseif (!empty($pedido['destinatario']))   $nome = htmlspecialchars($pedido['destinatario']);

        $tipoLink  = normaliza_tipo_php($pedido['tipo'] ?? '');
        $status    = htmlspecialchars($statusRaw);

        $classeCodigo = '';
        if (!empty($pedido['tipo'])) {
          if (stripos($pedido['tipo'], 'entrega') !== false)     $classeCodigo = 'codigo-entrega';
          elseif (stripos($pedido['tipo'], 'retirada') !== false) $classeCodigo = 'codigo-retirada';
        }

        $produtosHtml = produtos_html_php($pedido);
      ?>
      <tr>
        <td class="col-codigo">
          <span class="codigo-badge <?= $classeCodigo ?>">
            <?= htmlspecialchars($pedido['numero_pedido'] ?? '') ?>
          </span>
        </td>

        <td>
          <a href="/florV3/public/index.php?rota=detalhes&id=<?= $id ?>&tipo=<?= $tipoLink ?>">
            <?= $nome ?>
          </a>
        </td>

        <td>
          <select
            class="<?= $statusClasse ?>"
            data-status="<?= $status ?>"
            onchange="atualizarStatus(<?= $id ?>, '<?= $tipoLink ?>', this)">
            <?php
              $opcoes = ['Pendente', 'Produção', 'Pronto', 'Cancelado'];
              foreach ($opcoes as $opcao):
                $selected = strtolower($statusRaw) === strtolower($opcao) ? 'selected' : '';
                echo "<option value=\"$opcao\" $selected>$opcao</option>";
              endforeach;
            ?>
          </select>
        </td>

        <td>
          <div
            class="produtos-list"
            data-id="<?= $id ?>"
            data-tipo="<?= $tipoLink ?>"
            data-tipo-raw="<?= htmlspecialchars($pedido['tipo'] ?? '') ?>"
          >
            <?= $produtosHtml ?: '<em>—</em>' ?>
          </div>
        </td>

        <td>
          <div class="botoes-acoes">
            <button onclick="confirmarImpressao(<?= $id ?>, '<?= $tipoLink ?>')">🖨️ Imprimir</button>
            <button onclick="imprimirSegundaVia(<?= $id ?>, '<?= $tipoLink ?>')" style="background-color:#000;">🖨️ 2ª via</button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">← Voltar ao Painel</a>
</div>

<script>
/* ======== ÁUDIO ========= */
const audioEl = document.getElementById('notificacaoSom');
audioEl.volume = 1.0;
const SOUND_MP3 = '/florV3/public/assets/sounds/beep.mp3';
let somHabilitado = false;
const btnSom = document.getElementById('btnSom');

function carregarSom() {
  return new Promise((resolve) => {
    let done = false;
    const ok = ()=>{ if(!done){done=true;resolve();} };
    audioEl.oncanplaythrough = ok;
    audioEl.onerror = ok;
    audioEl.src = SOUND_MP3;
    audioEl.load();
    setTimeout(ok, 800);
  });
}
function tentarDesbloquearSom(){
  if (somHabilitado) return;
  audioEl.play().then(()=>{
    audioEl.pause(); audioEl.currentTime = 0;
    somHabilitado = true;
    if (btnSom){ btnSom.textContent = '🔊 Som habilitado'; btnSom.style.opacity = '0.7'; setTimeout(()=>btnSom.style.display='none',800); }
  }).catch(()=>{});
}
function tocarSomNotificacao(){ if(!somHabilitado) return; audioEl.currentTime=0; audioEl.play().catch(()=>{}); }
if (btnSom) btnSom.addEventListener('click', tentarDesbloquearSom);
document.addEventListener('click', tentarDesbloquearSom, { once:true });
carregarSom();

/* ======== HELPERS ========= */
function escapeHtml(str){ return String(str).replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[s])); }
function normalizaTipoJS(s){
  let t = String(s || '').toLowerCase().trim();
  t = t.replace(/^p[_-]/, '');
  const h = t.indexOf('-'); if (h >= 0) t = t.slice(h+1);
  return t.replace(/\s+/g,'');
}
function isProbablyHtml(str){
  const s = String(str || '');
  return /<!doctype|<html[\s>]|<head[\s>]|<body[\s>]|<\/(?:div|table|tr|td|ul|li)>/i.test(s);
}

// Detecta HTML/texto de erro vindo do backend (404 etc.)
function ehMensagemErroProdutos(str){
  const s = String(str || '');
  const plain = s.replace(/<[^>]+>/g, ' ').toLowerCase().replace(/\s+/g, ' ').trim();
  return /(p[aá]gina n[ãa]o encontrada|not found|erro 404|404|rota n[ãa]o encontrada)/.test(plain);
}

/** Extrai produtos (string, JSON, array, aceita <br>, vírgula, ;, \n) */
function extrairProdutos(p) {
  const pick = (...ks)=> {
    for (const k of ks) { if (p && p[k] != null && p[k] !== '') return p[k]; }
    return '';
  };
  let val = pick('produtos','produto','lista_produtos','itens','itens_pedido','descricao_produtos','detalhes_produtos','descricao');

  const linhas = [];

  if (Array.isArray(val)) {
    val.forEach(it=>{
      if (it && typeof it === 'object') {
        const q = it.qtd ?? it.quantidade ?? it.qtde ?? '';
        const n = it.nome ?? it.produto ?? it.descricao ?? '';
        const s = ((q ? (q+' x ') : '') + (n || '')).trim();
        if (s) linhas.push(s);
      } else if (it) {
        linhas.push(String(it));
      }
    });
  } else if (val != null && val !== '') {
    let s = String(val).trim();
    s = s.replace(/<br\s*\/?>/gi, '\n'); // normaliza <br>
    if (s.startsWith('[') || s.startsWith('{')) {
      try {
        const parsed = JSON.parse(s);
        if (Array.isArray(parsed)) {
          parsed.forEach(it=>{
            if (it && typeof it === 'object') {
              const q = it.qtd ?? it.quantidade ?? it.qtde ?? '';
              const n = it.nome ?? it.produto ?? it.descricao ?? '';
              const x = ((q ? (q+' x ') : '') + (n || '')).trim();
              if (x) linhas.push(x);
            } else if (it) {
              linhas.push(String(it));
            }
          });
        } else if (parsed && typeof parsed === 'object') {
          const q = parsed.qtd ?? parsed.quantidade ?? parsed.qtde ?? '';
          const n = parsed.nome ?? parsed.produto ?? parsed.descricao ?? '';
          const x = ((q ? (q+' x ') : '') + (n || '')).trim();
          if (x) linhas.push(x);
        }
      } catch(e) {
        s.split(/[,;\r?\n]+/).map(x=>x.trim()).filter(Boolean).forEach(x=>linhas.push(x));
      }
    } else {
      s.split(/[,;\r?\n]+/).map(x=>x.trim()).filter(Boolean).forEach(x=>linhas.push(x));
    }
  }

  return linhas;
}

/* ======== CACHE DE PRODUTOS ========= */
const produtosCache = new Map(); // id -> HTML dos produtos

function seedProdutosCacheDoDOM() {
  document.querySelectorAll('#lista-pedidos tr').forEach(tr=>{
    const link = tr.querySelector('a[href*="rota=detalhes"]');
    const prod = tr.querySelector('.produtos-list');
    if (link && prod) {
      const m = link.href.match(/[?&]id=(\d+)/);
      if (m) produtosCache.set(m[1], (prod.innerHTML || '').trim());
    }
  });
}
seedProdutosCacheDoDOM();

function getFinalProdutosHtml(id, novosHtml){
  const novo = (novosHtml || '').trim();
  const plain = novo.replace(/<br\s*\/?>/gi, ' ').replace(/<[^>]*>/g, ' ').trim();
  const contemHtmlPagina = /<!doctype|<html[\s>]/i.test(novo);
  const invalido = !novo || novo === '<em>—</em>' || ehMensagemErroProdutos(plain) || contemHtmlPagina;
  if (!invalido) {
    produtosCache.set(String(id), novo);
    return novo;
  }
  const cached = (produtosCache.get(String(id)) || '').trim();
  return cached || '<em>—</em>';
}

/* ======== LAZY-LOAD DOS PRODUTOS ======== */
function isPlaceholder(div){
  const texto = (div.textContent || '').trim();
  const raw   = (div.innerHTML || '').trim().toLowerCase();
  if (!texto) return true;
  if (texto === '—') return true;
  if (ehMensagemErroProdutos(texto)) return true; // trata erro como placeholder
  if (raw === '&mdash;' || raw === '<em>—</em>') return true;
  const em = div.querySelector('em');
  if (em && (em.textContent || '').trim() === '—') return true;
  return false;
}

/* ======== TIPOS / ROTAS CANDIDATAS (com fallback p/ ENTREGAS) ======== */
function pushVariacoesTipo(set, v){
  const arr = [v, v.toLowerCase(), v.toUpperCase(), v.charAt(0).toUpperCase()+v.slice(1)];
  arr.forEach(x=>{
    set.add(x);
    set.add('p_'+x);
    set.add('p-'+x);
  });
}

function gerarTiposCandidatos(tipo, tipoRaw){
  const out = new Set();
  const base = normalizaTipoJS(tipo || tipoRaw || '');
  const raw  = String(tipoRaw || '').trim();
  const t    = String(tipo || '').trim();

  if (base) out.add(base);
  if (t)    out.add(t);
  if (raw)  out.add(raw);
  if (base) { out.add('p_' + base); out.add('p-' + base); }

  const looksEntrega = /^entr/.test(base) || base.replace(/[aeiou]/g,'') === 'ntrg' || /deliv/.test(base) || /entrega/.test(raw.toLowerCase());
  const looksRetirada = /^reti?/.test(base) || /retirada/.test(raw.toLowerCase());

  if (looksEntrega) ['entrega','entregas','delivery','saida','saída','motoboy','courier'].forEach(v=>pushVariacoesTipo(out, v));
  if (looksRetirada) ['retirada','retira','coleta','pickup'].forEach(v=>pushVariacoesTipo(out, v));

  ['entrega','retirada'].forEach(v=>pushVariacoesTipo(out, v));
  return Array.from(out).filter(Boolean);
}

function rotasProdutosCandidatas(id, tipoCand){
  const q = (rota) => `/florV3/public/index.php?rota=${rota}&id=${encodeURIComponent(id)}${tipoCand ? `&tipo=${encodeURIComponent(tipoCand)}`:''}`;
  const rotas = [
    'produtos-pedido-json',
    'pedido-produtos-json',
    'produtos-entrega-json',
    'itens-pedido-json',
    'itens-entrega-json',
    'pedido-json',
    'detalhes-json'
  ].map(q);

  rotas.push(q('produtos-pedido'));
  rotas.push(q('detalhes'));
  rotas.push(q('detalhes-pedido'));
  rotas.push(q('imprimir-pedido'));

  return rotas;
}

async function parseTalvezJson(res){
  const ct = (res.headers.get('content-type') || '').toLowerCase();
  if (ct.includes('application/json')) return res.json();
  const txt = await res.text();
  try { return JSON.parse(txt); } catch { return txt; }
}

/* ======== PARSER DE HTML (fallback) ======== */
function parseProdutosDeHtml(html){
  try{
    const doc = new DOMParser().parseFromString(html, 'text/html');

    const linhas = [];
    const addLinha = (qtd, nome)=>{
      const n = (nome || '').replace(/\s+/g,' ').trim();
      const q = (qtd || '').toString().replace(',', '.').trim();
      if (!n) return;
      const isNum = /^\d+(\.\d+)?$/.test(q);
      linhas.push((isNum ? (q + ' x ') : '') + n);
    };

    const candidatos = Array.from(doc.querySelectorAll(
      '[data-produtos], .produtos-list, .lista-produtos, .produtos, .itens, .itens-pedido, #produtos, #itens, [id*="produto"], [class*="produto"]'
    ));
    for (const el of candidatos) {
      el.querySelectorAll('li').forEach(li=>{
        const t = li.textContent.trim();
        if (!t) return;
        const m = t.match(/^\s*(\d+(?:[.,]\d+)?)\s*x\s*(.+)$/i);
        if (m) addLinha(m[1], m[2]); else addLinha('', t);
      });
    }

    const tabelas = candidatos.flatMap(el=>Array.from(el.querySelectorAll('table'))).concat(Array.from(doc.querySelectorAll('table')));
    tabelas.forEach(tbl=>{
      const headerTxt = (tbl.querySelector('thead')?.innerText || tbl.rows[0]?.innerText || '').toLowerCase();
      const pareceItens = /(produto|descri|item).*(qtd|quant|qtde)|^(qtd|quant|qtde)/.test(headerTxt) || !headerTxt;
      if (!pareceItens) return;
      Array.from(tbl.querySelectorAll('tbody tr, tr')).forEach((tr, idx)=>{
        if (idx===0 && tbl.querySelector('thead')==null && tr.querySelectorAll('th').length) return;
        const tds = Array.from(tr.querySelectorAll('td')).map(td=>td.innerText.trim()).filter(Boolean);
        if (!tds.length) return;
        const qtd = tds.find(x=>/^\d+([.,]\d+)?$/.test(x)) || '';
        const nome = tds.reduce((a,b)=> b.length>a.length ? b : a, '');
        if (qtd || nome) addLinha(qtd, nome);
      });
    });

    if (linhas.length === 0) {
      const text = (doc.body?.innerText || '').split(/\r?\n/).map(s=>s.trim()).filter(Boolean);
      text.forEach(l=>{
        const m = l.match(/^\s*(\d+(?:[.,]\d+)?)\s*x\s+(.+?)\s*$/i);
        if (m) addLinha(m[1], m[2]);
      });
    }

    const limpas = Array.from(new Set(linhas.map(l=>l.replace(/\s+/g,' ').trim()))).filter(Boolean);
    return limpas;
  } catch { return []; }
}

/* ======== CARREGAMENTO DOS PRODUTOS ======== */
async function tentarCarregarProdutosDeUmPedido(div) {
  const id      = div.dataset.id;
  const tipo    = div.dataset.tipo || '';
  const tipoRaw = div.dataset.tipoRaw || '';
  if (!id) return;
  if (div.dataset.loading === '1') return;
  div.dataset.loading = '1';

  async function setIfHasLines(obj) {
    const linhas = extrairProdutos(obj);
    if (linhas.length === 1 && ehMensagemErroProdutos(linhas[0])) return false;

    const html = linhas.map(escapeHtml).join('<br>');
    if (html && !ehMensagemErroProdutos(html) && !isProbablyHtml(html)) {
      div.innerHTML = html;
      produtosCache.set(String(id), html);
      return true;
    }
    return false;
  }

  try {
    const tipos = gerarTiposCandidatos(tipo, tipoRaw);

    const tentar = async (url) => {
      try {
        const res = await fetch(url, { cache: 'no-store' });
        if (!res.ok) return false;

        const data = await parseTalvezJson(res);

        if (Array.isArray(data)) return await setIfHasLines({ itens: data });

        if (data && typeof data === 'object') {
          if (Array.isArray(data.produtos) && data.produtos.length) {
            if (await setIfHasLines({ itens: data.produtos })) return true;
          }
          return await setIfHasLines(data);
        }

        if (typeof data === 'string') {
          if (ehMensagemErroProdutos(data)) return false;

          if (isProbablyHtml(data) || /<[^>]+>/.test(data)) {
            const linhasHtml = parseProdutosDeHtml(data);
            if (linhasHtml.length) return await setIfHasLines({ itens: linhasHtml });
            return false;
          } else {
            const texto = data.replace(/<br\s*\/?>/gi, '\n').trim();
            const linhasTxt = texto.split(/[,;\r?\n]+/).map(x=>x.trim()).filter(Boolean);
            if (linhasTxt.length) return await setIfHasLines({ itens: linhasTxt });
            return false;
          }
        }
      } catch { return false; }
    };

    for (const cand of tipos) {
      const urls = rotasProdutosCandidatas(id, cand);
      for (const u of urls) {
        const ok = await tentar(u);
        if (ok) { div.dataset.loading = '0'; return; }
      }
    }

    {
      const urlsSemTipo = rotasProdutosCandidatas(id, '');
      for (const u of urlsSemTipo) {
        const ok = await tentar(u);
        if (ok) { div.dataset.loading = '0'; return; }
      }
    }

    try {
      const dataSel = document.querySelector('input[name="data"]')?.value || '';
      const url2 = `/florV3/public/index.php?rota=buscar-pedidos-dia-json&data=${encodeURIComponent(dataSel)}`;
      const res2 = await fetch(url2, { cache: 'no-store' });
      if (res2.ok) {
        const lista = await parseTalvezJson(res2);
        if (Array.isArray(lista)) {
          const item = lista.find(p => String(p.id) === String(id));
          if (item && await setIfHasLines(item)) { div.dataset.loading = '0'; return; }
        }
      }
    } catch {}

    const urlsHtmlBrutas = [
      `/florV3/public/index.php?rota=detalhes&id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}`,
      `/florV3/public/index.php?rota=detalhes&id=${encodeURIComponent(id)}`,
      `/florV3/public/index.php?rota=imprimir-pedido&id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}`,
      `/florV3/public/index.php?rota=imprimir-pedido&id=${encodeURIComponent(id)}`
    ];
    for (const urlH of urlsHtmlBrutas) {
      try {
        const res = await fetch(urlH, { cache: 'no-store' });
        if (!res.ok) continue;
        const html = await res.text();
        const linhas = parseProdutosDeHtml(html);
        if (linhas.length) {
          const htmlList = linhas.map(escapeHtml).join('<br>');
          div.innerHTML = htmlList;
          produtosCache.set(String(id), htmlList);
          div.dataset.loading = '0';
          return;
        }
      } catch {}
    }
  } finally {
    div.dataset.loading = '0';
  }
}

function carregarProdutosPreguicoso() {
  document.querySelectorAll('.produtos-list[data-id]').forEach(div => {
    if (isPlaceholder(div)) {
      tentarCarregarProdutosDeUmPedido(div);
    }
  });
}
document.addEventListener('DOMContentLoaded', carregarProdutosPreguicoso);

/* ======== CONTROLE DE REFRESH ========= */
let refreshPaused = false;
function pauseRefresh(){ refreshPaused = true; }
function resumeRefresh(){ refreshPaused = false; }

/* ======== STATUS / IMPRESSÃO ========= */
let selectTemp = null, prevStatusTemp = null;

function aplicarClasseStatus(selectEl, status) {
  const cls = ['status-pendente','status-producao','status-pronto'];
  selectEl.classList.remove(...cls);
  switch ((status || '').toLowerCase()) {
    case 'pendente':  selectEl.classList.add('status-pendente'); break;
    case 'produção':
    case 'producao':  selectEl.classList.add('status-producao');  break;
    case 'pronto':    selectEl.classList.add('status-pronto');    break;
  }
}

function atualizarStatus(id, tipo, el) {
  const novoStatus = (el && el.value) ? el.value : String(el || '').trim();
  const anterior   = (el && el.getAttribute('data-status')) || '';
  selectTemp = el; prevStatusTemp = anterior;

  const s = (novoStatus || '').toLowerCase();

  if (s === 'cancelado') {
    if (!confirm('Tem certeza que deseja CANCELAR este pedido?')) { if (el){ el.value = anterior; aplicarClasseStatus(el, anterior); } return; }
    const motivo = prompt('Informe o motivo do cancelamento:');
    if (!motivo) { alert('O motivo é obrigatório para cancelar.'); if (el){ el.value = anterior; aplicarClasseStatus(el, anterior);} return; }
    enviarStatus(id, tipo, novoStatus, { mensagem: motivo });
    return;
  }

  if (s === 'pronto') {
    if (!confirm('Confirmar que o pedido está PRONTO? Ele sairá da lista.')) { if (el){ el.value = anterior; aplicarClasseStatus(el, anterior);} return; }
    enviarStatus(id, tipo, novoStatus);
    return;
  }

  if (s === 'produção' || s === 'producao') {
    const modal = document.getElementById('modalResponsavel');
    document.getElementById('responsavelSelect').value = '';
    modal.dataset.acao  = 'status';
    modal.dataset.id    = String(id);
    modal.dataset.tipo  = String(tipo);
    modal.dataset.status= 'Produção';
    pauseRefresh();
    modal.style.display = 'block';
    return;
  }

  enviarStatus(id, tipo, novoStatus);
}

function enviarStatus(id, tipo, status, extras = {}, onOk) {
  const params = new URLSearchParams();
  params.append('id', id);
  params.append('tipo', tipo);
  params.append('status', status);
  if (extras.mensagem)    params.append('mensagem', extras.mensagem);
  if (extras.responsavel) params.append('responsavel', extras.responsavel);

  fetch('/florV3/public/index.php?rota=atualizar-status', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: params.toString()
  })
  .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.text(); })
  .then(() => {
    if (selectTemp) { selectTemp.setAttribute('data-status', status); aplicarClasseStatus(selectTemp, status); }
    if (typeof onOk === 'function') onOk();
    if (typeof atualizarTabelaAcompanhamento === 'function' && !refreshPaused) atualizarTabelaAcompanhamento();
  })
  .catch(err => {
    console.error('Falha ao atualizar status:', err);
    alert('Erro ao atualizar status. Tente novamente.');
    if (selectTemp && prevStatusTemp != null) { selectTemp.value = prevStatusTemp; aplicarClasseStatus(selectTemp, prevStatusTemp); }
  });
}

function confirmarResponsavel() {
  const modal = document.getElementById('modalResponsavel');
  const acao  = modal.dataset.acao || '';
  const resp  = document.getElementById('responsavelSelect').value;
  if (!acao) { fecharModal(); return; }
  if (!resp) { alert('Selecione um operador!'); return; }

  if (acao === 'status') {
    const id   = modal.dataset.id;
    const tipo = modal.dataset.tipo;
    const sts  = modal.dataset.status || 'Produção';
    enviarStatus(id, tipo, sts, { responsavel: resp }, () => {});
    fecharModal(); return;
  }

  if (acao === 'impressao') {
    const id   = modal.dataset.id;
    const tipo = modal.dataset.tipo;
    enviarStatus(id, tipo, 'Produção', { responsavel: resp }, () => {
      const ts = Date.now();
      window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}&_=${ts}`, '_blank');
    });
    fecharModal(); return;
  }

  fecharModal();
}

function fecharModal() {
  const modal = document.getElementById('modalResponsavel');
  modal.style.display = 'none';
  delete modal.dataset.acao; delete modal.dataset.id; delete modal.dataset.tipo; delete modal.dataset.status;
  resumeRefresh();
}

function confirmarImpressao(id, tipo) {
  const modal = document.getElementById('modalResponsavel');
  document.getElementById('responsavelSelect').value = '';
  modal.dataset.acao = 'impressao';
  modal.dataset.id   = String(id);
  modal.dataset.tipo = String(tipo);
  pauseRefresh();
  modal.style.display = 'block';
}

function imprimirSegundaVia(id, tipo) {
  const ts = Date.now();
  window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}&_=${ts}`, '_blank');
}

/* ======== NOTIFICAÇÕES FUTURAS ========= */
let notifIdsVistos = new Set(), notifUnreadPrev = 0, notifInitDone = false;

function toggleNotificationBox() {
  const box = document.getElementById('notification-box');
  box.style.display = box.style.display === 'block' ? 'none' : 'block';
}

function carregarNotificacoesFuturas() {
  fetch('/florV3/public/index.php?rota=notificacoes-futuras', { cache: 'no-store' })
    .then(r => r.json())
    .then(data => {
      const lista = document.getElementById('notification-list');
      const badge = document.getElementById('notification-count');
      lista.innerHTML = '';

      if (!Array.isArray(data)) data = [];

      if (data.length > 0) {
        badge.innerText = data.length;
        badge.style.display = 'inline-block';
        data.forEach(pedido => {
          const item = document.createElement('div');
          item.className = 'notification-item';
          item.style.cursor = 'pointer';
          if (!pedido.lido) item.style.backgroundColor = '#d5fcd5';
          item.innerHTML = `<strong>${escapeHtml(pedido.nome)}</strong><br>
              Produto: ${escapeHtml(pedido.produto ?? '')}<br>
              Tipo: ${escapeHtml(pedido.tipo ?? '')}<br>
              Data: ${escapeHtml(pedido.data ?? '')}`;
          item.onclick = () => {
            const tipo = normalizaTipoJS(pedido.tipo);
            const id = pedido.id;
            fetch('/florV3/public/index.php?rota=marcar-notificacao-lida', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}`
            }).then(() => {
              window.location.href = `/florV3/public/index.php?rota=detalhes&id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}`;
            });
          };
          lista.appendChild(item);
        });
      } else {
        badge.style.display = 'none';
        lista.innerHTML = '<p>Sem notificações futuras.</p>';
      }

      const idsAtuais = new Set(data.map(p => String(p.id)));
      const unreadCount = data.filter(p => !p.lido).length;

      if (!notifInitDone) { notifIdsVistos = idsAtuais; notifUnreadPrev = unreadCount; notifInitDone = true; return; }

      let temNovoId = false;
      idsAtuais.forEach(id => { if (!notifIdsVistos.has(id)) temNovoId = true; });
      const unreadAumentou = unreadCount > notifUnreadPrev;

      if ((temNovoId || unreadAumentou) && document.visibilityState === 'visible') tocarSomNotificacao();

      notifIdsVistos = idsAtuais; notifUnreadPrev = unreadCount;
    })
    .catch(()=>{});
}
carregarNotificacoesFuturas();
setInterval(carregarNotificacoesFuturas, 1000);

/* ======== BUSCA (live) ========= */
document.addEventListener("DOMContentLoaded", function () {
  const inputBusca = document.querySelector('input[name="produto"]');
  const containerPedidos = document.querySelector('#lista-pedidos');
  if (!inputBusca) return;

  inputBusca.addEventListener("keyup", function () {
    const termo = this.value;
    const data = document.querySelector('input[name="data"]').value;
    fetch(`/florV3/public/index.php?rota=buscar-pedidos-produto&produto=${encodeURIComponent(termo)}&data=${encodeURIComponent(data)}`, { cache: 'no-store' })
      .then(r => r.json())
      .then(data => {
        if (!Array.isArray(data)) return;
        let html = `
<table>
<tr>
  <th class="col-codigo">Código</th>
  <th>Cliente</th>
  <th>Status</th>
  <th>Produtos</th>
  <th>Ações</th>
</tr>`;
        const key = (p) => {
          if (p.ordem_fila !== null && p.ordem_fila !== undefined) return Number(p.ordem_fila);
          const dt = (p.data_abertura || '1970-01-01') + 'T' + (p.hora || '00:00:00');
          const ts = Date.parse(dt);
          return Number.isNaN(ts) ? parseInt(p.id || 0, 10) : ts;
        };
        data.sort((a,b)=>{const ka=key(a),kb=key(b); if(ka===kb) return (parseInt(b.id||0,10))-(parseInt(a.id||0,10)); return kb-ka;});

        data.forEach(pedido => {
          const nome   = pedido.nome || pedido.remetente || pedido.destinatario || '';
          const tipo   = normalizaTipoJS(pedido.tipo);
          const status = pedido.status ?? '';
          const numero = pedido.numero_pedido ?? '';
          const id     = pedido.id;

          const classeCodigo = tipo.includes('entrega') ? 'codigo-entrega' : (tipo.includes('retirada') ? 'codigo-retirada' : '');
          const linhas = extrairProdutos(pedido);
          const produtosHtml = linhas.map(escapeHtml).join('<br>');

          html += `
<tr>
  <td class="col-codigo"><span class="codigo-badge ${classeCodigo}">${escapeHtml(numero)}</span></td>
  <td><a href="/florV3/public/index.php?rota=detalhes&id=${id}&tipo=${tipo}">${escapeHtml(nome)}</a></td>
  <td>${escapeHtml(status)}</td>
  <td>
    <div class="produtos-list" data-id="${id}" data-tipo="${tipo}" data-tipo-raw="${escapeHtml(pedido.tipo || '')}">
      ${produtosHtml || '<em>—</em>'}
    </div>
  </td>
  <td><button onclick="confirmarImpressao(${id}, '${tipo}')">🖨️ Imprimir</button></td>
</tr>`;
        });
        html += '</table>';
        containerPedidos.innerHTML = html || '<p>Nenhum pedido encontrado.</p>';

        seedProdutosCacheDoDOM();
        carregarProdutosPreguicoso();
      });
  });
});

/* ======== AUTO-ATUALIZAÇÃO ========= */
let idsVistos = new Set();
(function inicializarIdsVistos() {
  const links = document.querySelectorAll('#lista-pedidos a[href*="rota=detalhes"]');
  links.forEach(a => { const m = a.href.match(/[?&]id=(\d+)/); if (m) idsVistos.add(m[1]); });
})();

function atualizarTabelaAcompanhamento() {
  if (refreshPaused) return;

  const data = document.querySelector('input[name="data"]').value;
  fetch(`/florV3/public/index.php?rota=buscar-pedidos-dia-json&data=${encodeURIComponent(data)}`, { cache: 'no-store' })
    .then(r => r.json())
    .then(pedidos => {
      if (!Array.isArray(pedidos)) return;

      const key = (p) => {
        if (p.ordem_fila !== null && p.ordem_fila !== undefined) return Number(p.ordem_fila);
        const dt = (p.data_abertura || '1970-01-01') + 'T' + (p.hora || '00:00:00');
        const ts = Date.parse(dt);
        return Number.isNaN(ts) ? parseInt(p.id || 0, 10) : ts;
      };
      pedidos.sort((a,b)=>{const ka=key(a),kb=key(b); if(ka===kb) return (parseInt(b.id||0,10))-(parseInt(a.id||0,10)); return kb-ka;});

      let chegouNovo = false;
      const idsAtuais = new Set();
      pedidos.forEach(p => { const pid = String(p.id || ''); idsAtuais.add(pid); if (!idsVistos.has(pid)) chegouNovo = true; });
      if (chegouNovo && document.visibilityState === 'visible') tocarSomNotificacao();
      idsVistos = idsAtuais;

      const tabela = document.querySelector('#lista-pedidos');
      let html = `
<table>
<tr>
  <th class="col-codigo">Código</th>
  <th>Cliente</th>
  <th>Status</th>
  <th>Produtos</th>
  <th>Ações</th>
</tr>`;
      pedidos.forEach(pedido => {
        const id    = pedido.id;
        const nome  = pedido.nome || pedido.remetente || pedido.destinatario || '';
        const tipo  = normalizaTipoJS(pedido.tipo);
        const numero= pedido.numero_pedido ?? '';
        const status= pedido.status ?? '';
        let statusClasse = '';
        switch ((status || '').toLowerCase()) {
          case 'pendente': statusClasse = 'status-pendente'; break;
          case 'produção': statusClasse = 'status-producao'; break;
          case 'pronto':   statusClasse = 'status-pronto';   break;
        }
        const classeCodigo = tipo.includes('entrega') ? 'codigo-entrega' : (tipo.includes('retirada') ? 'codigo-retirada' : '');

        const linhas = extrairProdutos(pedido);
        const produtosHtml = linhas.map(escapeHtml).join('<br>');
        const finalProdutos = getFinalProdutosHtml(id, produtosHtml);

        html += `
<tr>
  <td class="col-codigo"><span class="codigo-badge ${classeCodigo}">${escapeHtml(numero)}</span></td>
  <td><a href="/florV3/public/index.php?rota=detalhes&id=${id}&tipo=${tipo}">${escapeHtml(nome)}</a></td>
  <td>
    <select class="${statusClasse}" data-status="${escapeHtml(status)}" onchange="atualizarStatus(${id}, '${tipo}', this)">
      ${['Pendente', 'Produção', 'Pronto', 'Cancelado'].map(opt => {
        const sel = opt.toLowerCase() === (status || '').toLowerCase() ? 'selected' : '';
        return `<option value="${opt}" ${sel}>${opt}</option>`;
      }).join('')}
    </select>
  </td>
  <td>
    <div class="produtos-list" data-id="${id}" data-tipo="${tipo}" data-tipo-raw="${escapeHtml(pedido.tipo || '')}">
      ${finalProdutos}
    </div>
  </td>
  <td>
    <button onclick="confirmarImpressao(${id}, '${tipo}')">🖨️ Imprimir</button>
    <button onclick="imprimirSegundaVia(${id}, '${tipo}')" style="background-color:#000">🖨️ 2ª via</button>
  </td>
</tr>`;
      });
      html += '</table>';
      tabela.innerHTML = html;

      seedProdutosCacheDoDOM();
      carregarProdutosPreguicoso();
    });
}
atualizarTabelaAcompanhamento();
setInterval(atualizarTabelaAcompanhamento, 17000);

// Som via botão de login (se existir)
document.addEventListener('DOMContentLoaded', () => {
  const audioElLogin = document.getElementById('notificacaoSom');
  const btnLogin = document.querySelector('#btnLogin');
  if (btnLogin) {
    btnLogin.addEventListener('click', () => {
      audioElLogin.play().then(() => { audioElLogin.pause(); audioElLogin.currentTime = 0; window.somHabilitado = true; }).catch(()=>{});
    });
  }
});
</script>

<!-- Modal de Seleção de Operador -->
<div id="modalResponsavel" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999;">
  <div style="background:white; padding:20px; border-radius:8px; width:400px; margin:100px auto; position:relative;">
    <h3>Selecione o Operador</h3>
    <select id="responsavelSelect" style="width:100%; padding:10px; font-size:16px; background-color:white; color:black;">
      <option value="">Selecione...</option>
      <?php foreach ($operadores as $op): ?>
        <option value="<?= htmlspecialchars($op['nome']) ?>"><?= htmlspecialchars($op['nome']) ?></option>
      <?php endforeach; ?>
    </select>
    <div style="margin-top:20px; text-align:right;">
      <button onclick="confirmarResponsavel()">Confirmar</button>
      <button onclick="fecharModal()">Cancelar</button>
    </div>
  </div>
</div>

</body>
</html>
