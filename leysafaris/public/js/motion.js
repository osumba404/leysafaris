(function initSiteMotion() {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const STAGGER_GROUPS = [
    ['.safari-grid', '.safari-card'],
    ['.why-us__grid', '.feature-card'],
    ['.experience-grid', '.feature-card'],
    ['.blog-grid', '.blog-card'],
    ['.destination-grid', '.destination-card'],
    ['.dest-mosaic__grid', '.dest-mosaic__item'],
    ['.inspire-hub__grid', '.inspire-card'],
    ['.accordion', '.accordion__item'],
    ['.review-carousel__track', '.review-card'],
    ['.value-pills', 'li'],
    ['.footer__grid', ':scope > *'],
  ];

  const REVEAL_SELECTORS = [
    '.section-header',
    '.package-cta',
    '.review-widget',
    '.filter-bar',
    '.page-search',
    '.hero-proposal',
    '.newsletter-block',
    '.trip-type-chips',
    '.inquiry-form',
    '.package-sidebar',
  ];

  function prepare(el, delay = 0, options = {}) {
    el.classList.add('motion-reveal');
    if (options.soft) el.classList.add('motion-reveal--soft');
    if (options.scale) el.classList.add('motion-reveal--scale');
    if (delay > 0) el.style.setProperty('--motion-delay', `${delay}ms`);
  }

  function collectTargets() {
    const seen = new Set();

    STAGGER_GROUPS.forEach(([gridSelector, itemSelector]) => {
      document.querySelectorAll(gridSelector).forEach((grid) => {
        grid.querySelectorAll(itemSelector).forEach((item, index) => {
          if (seen.has(item)) return;
          prepare(item, Math.min(index * 75, 450));
          seen.add(item);
        });
      });
    });

    REVEAL_SELECTORS.forEach((selector) => {
      document.querySelectorAll(selector).forEach((el) => {
        if (seen.has(el)) return;
        prepare(el, 0, { soft: true });
        seen.add(el);
      });
    });

    return [...seen];
  }

  function run() {
    document.body.classList.add('is-page-loaded');

    document.querySelectorAll('.hero__content').forEach((heroContent) => {
      heroContent.classList.add('motion-hero-in');
    });

    document.querySelectorAll('.flash').forEach((flash) => {
      flash.classList.add('motion-flash-in');
    });

    if (reducedMotion) {
      document.documentElement.classList.add('motion-reduced');
      return;
    }

    const targets = collectTargets();
    if (!targets.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        });
      },
      {
        rootMargin: '0px 0px -7% 0px',
        threshold: 0.1,
      }
    );

    targets.forEach((target) => observer.observe(target));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', run);
  } else {
    run();
  }
})();
