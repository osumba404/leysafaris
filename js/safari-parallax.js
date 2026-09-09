(function initSafariParallax() {
  const world = document.getElementById('safari-world');
  const scene = world?.querySelector('.safari-world__scene');
  const layers = world ? [...world.querySelectorAll('.safari-world__layer')] : [];

  if (!world || !scene || !layers.length) return;

  document.body.classList.add('safari-parallax-active');

  const images = [...world.querySelectorAll('img')];
  let imagesReady = 0;

  function markImageReady() {
    imagesReady += 1;
    if (imagesReady >= 1) {
      document.body.classList.add('safari-parallax-ready');
    }
  }

  images.forEach((img) => {
    if (img.complete) {
      markImageReady();
    } else {
      img.addEventListener('load', markImageReady, { once: true });
      img.addEventListener('error', markImageReady, { once: true });
    }
  });

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const layerConfig = layers.map((layer, i) => ({
    el: layer,
    depth: parseFloat(layer.dataset.depth) || (i + 1) * 0.12,
    z: parseFloat(getComputedStyle(layer).getPropertyValue('--layer-z')) || -(800 - i * 180),
    scale: parseFloat(getComputedStyle(layer).getPropertyValue('--layer-scale')) || 1.2,
  }));

  const state = {
    scrollY: 0,
    mouseX: 0,
    mouseY: 0,
    targetScrollY: 0,
    targetMouseX: 0,
    targetMouseY: 0,
    docHeight: 1,
  };

  const lerp = (a, b, t) => a + (b - a) * t;

  function measure() {
    state.docHeight = Math.max(
      document.documentElement.scrollHeight - window.innerHeight,
      1
    );
  }

  function onScroll() {
    state.targetScrollY = window.scrollY;
  }

  function onMouseMove(e) {
    const cx = window.innerWidth / 2;
    const cy = window.innerHeight / 2;
    state.targetMouseX = (e.clientX - cx) / cx;
    state.targetMouseY = (e.clientY - cy) / cy;
  }

  function onResize() {
    measure();
  }

  function applyTransforms() {
    const progress = state.scrollY / state.docHeight;
    const tiltX = progress * 7 - 1.5;
    const tiltY = state.mouseX * 2.5;

    scene.style.transform = `
      rotateX(${tiltX}deg)
      rotateY(${tiltY}deg)
      translate3d(0, ${state.scrollY * -0.04}px, 0)
    `;

    layerConfig.forEach(({ el, depth, z, scale }) => {
      const parallaxY = state.scrollY * depth * 0.55;
      const parallaxX = state.mouseX * depth * 28;
      const mouseLift = state.mouseY * depth * 12;

      el.style.transform = `
        translate3d(${parallaxX}px, ${parallaxY + mouseLift}px, ${z})
        scale(${scale})
      `;
    });
  }

  function tick() {
    const ease = reducedMotion ? 1 : 0.08;

    state.scrollY = lerp(state.scrollY, state.targetScrollY, ease);
    state.mouseX = lerp(state.mouseX, state.targetMouseX, ease);
    state.mouseY = lerp(state.mouseY, state.targetMouseY, ease);

    applyTransforms();
    requestAnimationFrame(tick);
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('mousemove', onMouseMove, { passive: true });
  window.addEventListener('resize', onResize, { passive: true });

  measure();
  onScroll();
  state.scrollY = state.targetScrollY;
  applyTransforms();
  requestAnimationFrame(tick);
})();
