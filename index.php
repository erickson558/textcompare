<?php

declare(strict_types=1);

require_once __DIR__ . '/backend/src/Application/Version.php';

use TextCompare\Application\Version;

$appVersion = Version::value();
?><!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Comparador de textos moderno en PHP con resaltado de diferencias." />
  <title>TextCompare Pro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/assets/css/styles.css" />
</head>
<body>
  <div class="noise-overlay"></div>

  <header class="topbar reveal">
    <div class="brand-block">
      <span class="badge">PHP + JS</span>
      <h1>TextCompare Pro</h1>
      <p>Comparador de textos con arquitectura limpia y resultados en tiempo real.</p>
    </div>
    <div class="toolbar">
      <button id="themeToggle" class="btn secondary" type="button" aria-label="Cambiar tema">Modo oscuro</button>
      <button id="swapBtn" class="btn ghost" type="button">Intercambiar</button>
    </div>
  </header>

  <main class="layout">
    <section class="panel input-panel reveal">
      <div class="panel-head">
        <h2>Entradas</h2>
        <div class="panel-actions">
          <button id="clearBtn" class="btn ghost" type="button">Limpiar</button>
          <button id="compareBtn" class="btn primary" type="button">Comparar</button>
        </div>
      </div>
      <div class="editor-grid">
        <label>
          <span>Texto A</span>
          <textarea id="leftText" placeholder="Pega aqui el primer texto..."></textarea>
        </label>
        <label>
          <span>Texto B</span>
          <textarea id="rightText" placeholder="Pega aqui el segundo texto..."></textarea>
        </label>
      </div>
    </section>

    <section class="panel output-panel reveal">
      <div class="panel-head">
        <h2>Resultados</h2>
        <div class="stats" id="statsBar">
          <span>Similitud: --%</span>
          <span>Lineas iguales: --</span>
          <span>Lineas A/B: --/--</span>
        </div>
      </div>
      <div class="legend">
        <span class="chip equal">Sin cambios</span>
        <span class="chip add">Agregado</span>
        <span class="chip remove">Eliminado</span>
      </div>
      <div id="diffResult" class="diff-result"></div>
    </section>
  </main>

  <footer class="footer reveal">
    <span id="appVersion">Version <?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?></span>
    <span>Licencia Apache-2.0</span>
  </footer>

  <script>
    window.TEXTCOMPARE_CONFIG = {
      compareEndpoint: 'backend/api/compare.php',
      versionEndpoint: 'backend/api/version.php',
      fallbackVersion: '<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>'
    };
  </script>
  <script src="frontend/assets/js/app.js" defer></script>
</body>
</html>
