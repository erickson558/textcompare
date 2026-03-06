<?php

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
  <div class="grid-overlay"></div>

  <header class="hero reveal">
    <h1>Text Compare!</h1>
    <div class="hero-actions">
      <button id="themeToggle" class="btn icon" type="button" aria-label="Cambiar tema">Tema</button>
    </div>
  </header>

  <main class="layout reveal">
    <section class="actions-row">
      <button id="emailBtn" class="btn muted" type="button">Email this comparison</button>
      <div class="spacer"></div>
      <button id="copyLeftBtn" class="btn ghost" type="button">Copy left</button>
      <button id="copyRightBtn" class="btn ghost" type="button">Copy right</button>
      <button id="showDiffOnlyBtn" class="btn ghost" type="button">Differences only</button>
    </section>

    <section class="compare-wrap">
      <div class="results-grid">
        <article class="result-panel">
          <header class="result-head">Text A</header>
          <div id="leftResult" class="result-body" aria-live="polite"></div>
        </article>
        <article class="result-panel">
          <header class="result-head">Text B</header>
          <div id="rightResult" class="result-body" aria-live="polite"></div>
        </article>
      </div>

      <div id="statsBar" class="stats-bar">
        <span>Similarity: --%</span>
        <span>Equal lines: --</span>
        <span>Lines A/B: --/--</span>
      </div>

      <div id="editorsArea" class="editors-grid">
        <label class="editor-box">
          <span>Source A</span>
          <textarea id="leftText" placeholder="Paste first text here..."></textarea>
        </label>
        <label class="editor-box">
          <span>Source B</span>
          <textarea id="rightText" placeholder="Paste second text here..."></textarea>
        </label>
      </div>

      <section class="controls-row">
        <button id="editToggleBtn" class="btn ghost" type="button">Edit texts ...</button>
        <button id="swapBtn" class="btn ghost" type="button">Switch texts</button>
        <button id="compareBtn" class="btn primary" type="button">Compare!</button>
        <button id="clearBtn" class="btn ghost" type="button">Clear all</button>
      </section>
    </section>
  </main>

  <footer class="footer reveal">
    <span id="appVersion">Version <?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?></span>
    <span>Apache License 2.0</span>
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
