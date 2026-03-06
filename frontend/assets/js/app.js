(() => {
  const leftText = document.getElementById('leftText');
  const rightText = document.getElementById('rightText');
  const compareBtn = document.getElementById('compareBtn');
  const clearBtn = document.getElementById('clearBtn');
  const swapBtn = document.getElementById('swapBtn');
  const themeToggle = document.getElementById('themeToggle');
  const diffResult = document.getElementById('diffResult');
  const statsBar = document.getElementById('statsBar');
  const appVersion = document.getElementById('appVersion');

  const cfg = window.TEXTCOMPARE_CONFIG || {};
  const themeKey = 'textcompare-theme';

  function applyTheme(theme) {
    const selected = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', selected);
    themeToggle.textContent = selected === 'dark' ? 'Modo claro' : 'Modo oscuro';
    localStorage.setItem(themeKey, selected);
  }

  function escapeHtml(text) {
    return text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/\"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function setStats(stats) {
    statsBar.innerHTML = [
      `<span>Similitud: ${stats.similarity ?? '--'}%</span>`,
      `<span>Lineas iguales: ${stats.equalLines ?? '--'}</span>`,
      `<span>Lineas A/B: ${stats.leftLines ?? '--'}/${stats.rightLines ?? '--'}</span>`
    ].join('');
  }

  function renderDiff(operations = []) {
    if (!operations.length) {
      diffResult.innerHTML = '<div class="diff-line equal">Sin diferencias detectadas.</div>';
      return;
    }

    diffResult.innerHTML = operations
      .map((op, index) => {
        const prefix = op.type === 'add' ? '+ ' : op.type === 'remove' ? '- ' : '  ';
        return `<div class="diff-line ${op.type}" style="animation-delay:${Math.min(index * 0.015, 0.4)}s">${escapeHtml(prefix + (op.text || ''))}</div>`;
      })
      .join('');
  }

  async function compareText() {
    compareBtn.disabled = true;
    compareBtn.textContent = 'Comparando...';

    try {
      const response = await fetch(cfg.compareEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          leftText: leftText.value,
          rightText: rightText.value
        })
      });

      const json = await response.json();

      if (!response.ok) {
        throw new Error(json.error || 'Error al comparar textos.');
      }

      const data = json.data || {};
      renderDiff(data.operations || []);
      setStats(data.stats || {});
    } catch (error) {
      renderDiff([{ type: 'remove', text: `Error: ${error.message}` }]);
      setStats({ similarity: '--', equalLines: '--', leftLines: '--', rightLines: '--' });
    } finally {
      compareBtn.disabled = false;
      compareBtn.textContent = 'Comparar';
    }
  }

  async function syncVersion() {
    appVersion.textContent = `Version ${cfg.fallbackVersion || 'V0.0.0'}`;

    if (!cfg.versionEndpoint) {
      return;
    }

    try {
      const response = await fetch(cfg.versionEndpoint);
      const json = await response.json();
      if (json.version) {
        appVersion.textContent = `Version ${json.version}`;
      }
    } catch (_error) {
      // Keep fallback version when backend endpoint is unavailable.
    }
  }

  compareBtn.addEventListener('click', compareText);
  clearBtn.addEventListener('click', () => {
    leftText.value = '';
    rightText.value = '';
    renderDiff([]);
    setStats({ similarity: '--', equalLines: '--', leftLines: '--', rightLines: '--' });
  });
  swapBtn.addEventListener('click', () => {
    const temp = leftText.value;
    leftText.value = rightText.value;
    rightText.value = temp;
  });

  themeToggle.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme');
    applyTheme(current === 'dark' ? 'light' : 'dark');
  });

  const initialTheme = localStorage.getItem(themeKey) || 'light';
  applyTheme(initialTheme);
  renderDiff([]);
  setStats({ similarity: '--', equalLines: '--', leftLines: '--', rightLines: '--' });
  syncVersion();
})();
