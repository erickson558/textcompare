(() => {
  const leftText = document.getElementById('leftText');
  const rightText = document.getElementById('rightText');
  const compareBtn = document.getElementById('compareBtn');
  const clearBtn = document.getElementById('clearBtn');
  const swapBtn = document.getElementById('swapBtn');
  const editToggleBtn = document.getElementById('editToggleBtn');
  const emailBtn = document.getElementById('emailBtn');
  const copyLeftBtn = document.getElementById('copyLeftBtn');
  const copyRightBtn = document.getElementById('copyRightBtn');
  const showDiffOnlyBtn = document.getElementById('showDiffOnlyBtn');
  const editorsArea = document.getElementById('editorsArea');
  const themeToggle = document.getElementById('themeToggle');
  const leftResult = document.getElementById('leftResult');
  const rightResult = document.getElementById('rightResult');
  const statsBar = document.getElementById('statsBar');
  const appVersion = document.getElementById('appVersion');

  const cfg = window.TEXTCOMPARE_CONFIG || {};
  const themeKey = 'textcompare-theme';
  const editorVisibilityKey = 'textcompare-editors-visible';
  const diffOnlyKey = 'textcompare-diff-only';

  let isShowingDiffOnly = false;
  let syncLock = false;

  function applyTheme(theme) {
    const selected = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', selected);
    themeToggle.textContent = selected === 'dark' ? 'Light' : 'Dark';
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
      `<span>Similarity: ${stats.similarity ?? '--'}%</span>`,
      `<span>Equal lines: ${stats.equalLines ?? '--'}</span>`,
      `<span>Lines A/B: ${stats.leftLines ?? '--'}/${stats.rightLines ?? '--'}</span>`
    ].join('');
  }

  function alignRows(operations = []) {
    const rows = [];
    let leftLine = 0;
    let rightLine = 0;

    for (const op of operations) {
      if (op.type === 'equal') {
        leftLine += 1;
        rightLine += 1;
        rows.push({
          type: 'equal',
          leftLine,
          rightLine,
          leftText: op.text || '',
          rightText: op.text || ''
        });
        continue;
      }

      if (op.type === 'remove') {
        leftLine += 1;
        rows.push({
          type: 'remove',
          leftLine,
          rightLine: '',
          leftText: op.text || '',
          rightText: ''
        });
        continue;
      }

      rightLine += 1;
      rows.push({
        type: 'add',
        leftLine: '',
        rightLine,
        leftText: '',
        rightText: op.text || ''
      });
    }

    return rows;
  }

  function renderResultPanel(container, rows, side) {
    const html = rows
      .filter((row) => !isShowingDiffOnly || row.type !== 'equal')
      .map((row, index) => {
        const lineNumber = side === 'left' ? row.leftLine : row.rightLine;
        const text = side === 'left' ? row.leftText : row.rightText;

        return [
          `<div class="result-line ${row.type}" style="animation-delay:${Math.min(index * 0.01, 0.35)}s">`,
          `<span class="line-number">${lineNumber === '' ? '&nbsp;' : lineNumber}</span>`,
          `<span class="line-text">${escapeHtml(text)}</span>`,
          '</div>'
        ].join('');
      })
      .join('');

    container.innerHTML = html || '<div class="result-line equal"><span class="line-number">1</span><span class="line-text">No differences detected.</span></div>';
  }

  function renderDiff(operations = []) {
    if (!operations.length) {
      const empty = [{ type: 'equal', leftLine: 1, rightLine: 1, leftText: 'No differences detected.', rightText: 'No differences detected.' }];
      renderResultPanel(leftResult, empty, 'left');
      renderResultPanel(rightResult, empty, 'right');
      return;
    }

    const rows = alignRows(operations);
    renderResultPanel(leftResult, rows, 'left');
    renderResultPanel(rightResult, rows, 'right');
  }

  function setEditorsVisible(isVisible) {
    editorsArea.classList.toggle('hidden', !isVisible);
    editToggleBtn.textContent = isVisible ? 'Hide texts' : 'Edit texts ...';
    localStorage.setItem(editorVisibilityKey, isVisible ? '1' : '0');
  }

  function setDiffOnly(enabled) {
    isShowingDiffOnly = enabled;
    showDiffOnlyBtn.classList.toggle('active', enabled);
    showDiffOnlyBtn.textContent = enabled ? 'Show all lines' : 'Differences only';
    localStorage.setItem(diffOnlyKey, enabled ? '1' : '0');
  }

  function makeMailtoUrl() {
    const subject = encodeURIComponent('Text comparison result');
    const body = encodeURIComponent(
      ['Left text:', leftText.value, '', 'Right text:', rightText.value].join('\n')
    );

    return `mailto:?subject=${subject}&body=${body}`;
  }

  async function copyText(text, button, idleLabel) {
    try {
      await navigator.clipboard.writeText(text);
      button.textContent = 'Copied';
      setTimeout(() => {
        button.textContent = idleLabel;
      }, 1200);
    } catch (_error) {
      button.textContent = 'Clipboard blocked';
      setTimeout(() => {
        button.textContent = idleLabel;
      }, 1600);
    }
  }

  function syncScroll(source, target) {
    if (syncLock) {
      return;
    }

    syncLock = true;
    target.scrollTop = source.scrollTop;
    requestAnimationFrame(() => {
      syncLock = false;
    });
  }

  async function compareText() {
    compareBtn.disabled = true;
    compareBtn.textContent = 'Comparing...';

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
      compareBtn.textContent = 'Compare!';
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

  editToggleBtn.addEventListener('click', () => {
    const hidden = editorsArea.classList.contains('hidden');
    setEditorsVisible(hidden);
  });

  swapBtn.addEventListener('click', () => {
    const temp = leftText.value;
    leftText.value = rightText.value;
    rightText.value = temp;
    compareText();
  });

  emailBtn.addEventListener('click', () => {
    window.location.href = makeMailtoUrl();
  });

  copyLeftBtn.addEventListener('click', () => {
    copyText(leftText.value, copyLeftBtn, 'Copy left');
  });

  copyRightBtn.addEventListener('click', () => {
    copyText(rightText.value, copyRightBtn, 'Copy right');
  });

  showDiffOnlyBtn.addEventListener('click', () => {
    setDiffOnly(!isShowingDiffOnly);
    compareText();
  });

  leftResult.addEventListener('scroll', () => syncScroll(leftResult, rightResult));
  rightResult.addEventListener('scroll', () => syncScroll(rightResult, leftResult));

  themeToggle.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme');
    applyTheme(current === 'dark' ? 'light' : 'dark');
  });

  const initialTheme = localStorage.getItem(themeKey) || 'light';
  const editorsVisible = localStorage.getItem(editorVisibilityKey) !== '0';
  const startDiffOnly = localStorage.getItem(diffOnlyKey) === '1';

  applyTheme(initialTheme);
  setEditorsVisible(editorsVisible);
  setDiffOnly(startDiffOnly);
  renderDiff([]);
  setStats({ similarity: '--', equalLines: '--', leftLines: '--', rightLines: '--' });
  syncVersion();
})();
