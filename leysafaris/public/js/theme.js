/**
 * Leyla Safari Tours - theme (load in <head> before CSS to avoid flash)
 */
(function () {
  var STORAGE_KEY = 'leyla-theme';

  function getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function getStoredTheme() {
    try {
      var stored = localStorage.getItem(STORAGE_KEY);
      return stored === 'dark' || stored === 'light' ? stored : null;
    } catch (e) {
      return null;
    }
  }

  function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.style.colorScheme = theme;
  }

  window.LeylaTheme = {
    storageKey: STORAGE_KEY,
    getSystemTheme: getSystemTheme,
    getStoredTheme: getStoredTheme,
    getActiveTheme: function () {
      return document.documentElement.getAttribute('data-theme') || getSystemTheme();
    },
    applyTheme: applyTheme,
    toggle: function () {
      var next = this.getActiveTheme() === 'dark' ? 'light' : 'dark';
      try {
        localStorage.setItem(STORAGE_KEY, next);
      } catch (e) {
        /* ignore */
      }
      applyTheme(next);
      document.dispatchEvent(new CustomEvent('leyla-theme-change', { detail: { theme: next } }));
      return next;
    },
    initToggle: function () {
      var btn = document.getElementById('theme-toggle');
      if (!btn || btn.dataset.bound === '1') return;
      btn.dataset.bound = '1';

      btn.addEventListener('click', function () {
        window.LeylaTheme.toggle();
        if (typeof lucide !== 'undefined') lucide.createIcons();
      });

      document.addEventListener('leyla-theme-change', function () {
        var theme = window.LeylaTheme.getActiveTheme();
        btn.setAttribute('aria-label', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
      });
    },
  };

  applyTheme(getStoredTheme() || getSystemTheme());

  if (getStoredTheme() === null) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (event) {
      if (getStoredTheme() !== null) return;
      applyTheme(event.matches ? 'dark' : 'light');
    });
  }
})();
