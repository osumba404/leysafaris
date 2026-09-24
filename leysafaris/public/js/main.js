document.addEventListener('DOMContentLoaded', () => {
  if (window.LeylaTheme) {
    window.LeylaTheme.initToggle();
  }

  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

  initMobileNav();
  initHeaderScroll();
  initPasswordToggles();
  initAccordions();
  initInquiryForm();
  initSmoothScroll();
  initLazySections();
  initReviewCarousel();
  initHeroVideoMute();
  initHeroSlider();
});

function initMobileNav() {
  const toggle = document.getElementById('nav-toggle');
  const nav = document.getElementById('nav');
  if (!toggle || !nav) return;

  toggle.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('nav--open');
    toggle.setAttribute('aria-expanded', String(isOpen));
    toggle.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');

    const icon = toggle.querySelector('[data-lucide]');
    if (icon && typeof lucide !== 'undefined') {
      icon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
      lucide.createIcons();
    }
  });

  nav.querySelectorAll('.nav__link').forEach(link => {
    link.addEventListener('click', () => {
      nav.classList.remove('nav--open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Open menu');
      const icon = toggle.querySelector('[data-lucide]');
      if (icon && typeof lucide !== 'undefined') {
        icon.setAttribute('data-lucide', 'menu');
        lucide.createIcons();
      }
    });
  });
}

function initHeaderScroll() {
  const header = document.getElementById('header');
  if (!header) return;

  let ticking = false;
  const onScroll = () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        header.classList.toggle('header--scrolled', window.scrollY > 20);
        ticking = false;
      });
      ticking = true;
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

function initPasswordToggles() {
  document.querySelectorAll('.password-field').forEach((field) => {
    const input = field.querySelector('input[type="password"], input[type="text"]');
    const toggle = field.querySelector('.password-field__toggle');
    const icon = toggle?.querySelector('[data-lucide]');

    if (!input || !toggle || !icon) return;

    toggle.addEventListener('click', () => {
      const isVisible = input.type === 'text';

      input.type = isVisible ? 'password' : 'text';
      toggle.setAttribute('aria-pressed', String(!isVisible));
      toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');

      icon.setAttribute('data-lucide', isVisible ? 'eye' : 'eye-off');

      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });
  });
}

function initAccordions() {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.querySelectorAll('.accordion__panel').forEach((panel) => {
    if (!panel.hidden) {
      panel.classList.add('is-open');
      panel.style.height = 'auto';
      panel.style.opacity = '1';
    }
  });

  document.querySelectorAll('.accordion__trigger').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const panel = document.getElementById(trigger.getAttribute('aria-controls'));
      const accordion = trigger.closest('.accordion');
      const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

      accordion?.querySelectorAll('.accordion__trigger').forEach((other) => {
        if (other === trigger) return;

        other.setAttribute('aria-expanded', 'false');
        const otherPanel = document.getElementById(other.getAttribute('aria-controls'));
        if (otherPanel) closeAccordionPanel(otherPanel, reducedMotion);
      });

      trigger.setAttribute('aria-expanded', String(!isExpanded));

      if (!panel) return;

      if (isExpanded) {
        closeAccordionPanel(panel, reducedMotion);
      } else {
        openAccordionPanel(panel, reducedMotion);
      }
    });
  });
}

function openAccordionPanel(panel, reducedMotion = false) {
  if (reducedMotion) {
    panel.hidden = false;
    panel.classList.add('is-open');
    panel.style.height = '';
    panel.style.opacity = '';
    return;
  }

  panel.hidden = false;
  panel.classList.add('is-open');
  panel.style.height = '0px';
  panel.style.opacity = '0';

  requestAnimationFrame(() => {
    panel.style.height = `${panel.scrollHeight}px`;
    panel.style.opacity = '1';
  });

  const onEnd = (event) => {
    if (event.propertyName !== 'height') return;
    panel.style.height = 'auto';
    panel.removeEventListener('transitionend', onEnd);
  };

  panel.addEventListener('transitionend', onEnd);
}

function closeAccordionPanel(panel, reducedMotion = false) {
  if (reducedMotion || panel.hidden) {
    panel.hidden = true;
    panel.classList.remove('is-open');
    panel.style.height = '';
    panel.style.opacity = '';
    return;
  }

  panel.style.height = `${panel.scrollHeight}px`;
  panel.style.opacity = '1';

  requestAnimationFrame(() => {
    panel.classList.remove('is-open');
    panel.style.height = '0px';
    panel.style.opacity = '0';
  });

  const onEnd = (event) => {
    if (event.propertyName !== 'height') return;
    panel.hidden = true;
    panel.style.height = '';
    panel.style.opacity = '';
    panel.removeEventListener('transitionend', onEnd);
  };

  panel.addEventListener('transitionend', onEnd);
}

function initInquiryForm() {
  const form = document.getElementById('inquiry-form');
  const note = document.getElementById('form-note');
  if (!form || !note) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const name = form.querySelector('#name');
    const email = form.querySelector('#email');

    note.className = 'form-note';

    if (!name.value.trim()) {
      note.textContent = 'Please enter your name.';
      note.classList.add('form-note--error');
      name.focus();
      return;
    }

    if (!email.value.trim() || !email.validity.valid) {
      note.textContent = 'Please enter a valid email address.';
      note.classList.add('form-note--error');
      email.focus();
      return;
    }

    note.textContent = 'Thank you! Your inquiry has been received. We will be in touch shortly.';
    note.classList.add('form-note--success');
    form.reset();
  });
}

function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const targetId = anchor.getAttribute('href');
      if (targetId === '#') return;

      const target = document.querySelector(targetId);
      if (!target) return;

      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth' });
    });
  });
}

function initLazySections() {
  const gallery = document.querySelector('.gallery-strip');
  if (gallery && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      ([entry]) => {
        gallery.classList.toggle('is-paused', !entry.isIntersecting);
      },
      { rootMargin: '100px' }
    );
    observer.observe(gallery);
  }
}

function initReviewCarousel() {
  const carousel = document.querySelector('[data-review-carousel]');
  if (!carousel) return;

  const track = carousel.querySelector('[data-carousel-track]');
  const prev = carousel.querySelector('[data-carousel-prev]');
  const next = carousel.querySelector('[data-carousel-next]');
  if (!track || !prev || !next) return;

  const scrollAmount = () => Math.min(track.clientWidth * 0.85, 520);

  prev.addEventListener('click', () => {
    track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
  });

  next.addEventListener('click', () => {
    track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
  });
}

function enforceHeroVideoMute(video) {
  if (!video) return;

  video.muted = true;
  video.defaultMuted = true;
  video.volume = 0;
  video.setAttribute('muted', '');
  video.setAttribute('playsinline', '');
}

function initHeroVideoMute() {
  document.querySelectorAll('.hero-slider__video, .hero__video').forEach((video) => {
    enforceHeroVideoMute(video);
    video.addEventListener('volumechange', () => {
      if (!video.muted || video.volume > 0) {
        enforceHeroVideoMute(video);
      }
    });
  });
}

function playHeroVideo(video) {
  if (!video) return;

  enforceHeroVideoMute(video);

  const attempt = () => {
    const playPromise = video.play();
    if (playPromise !== undefined) {
      playPromise.catch(() => {});
    }
  };

  if (video.readyState >= 2) {
    attempt();
  } else {
    video.addEventListener('loadeddata', attempt, { once: true });
    video.addEventListener('canplay', attempt, { once: true });
  }
}

function syncHeroEmbed(slide, isActive) {
  const iframe = slide.querySelector('.hero-slider__embed iframe');
  if (!iframe) return;

  const embedSrc = iframe.getAttribute('data-embed-src');
  if (!embedSrc) return;

  if (isActive) {
    if (!iframe.getAttribute('src')) {
      iframe.setAttribute('src', embedSrc);
    }
  } else if (iframe.hasAttribute('src')) {
    iframe.removeAttribute('src');
  }
}

const youtubeApiQueue = [];

function whenYouTubeReady(callback) {
  if (window.YT && window.YT.Player) {
    callback();
    return;
  }

  youtubeApiQueue.push(callback);

  if (!document.querySelector('script[src*="youtube.com/iframe_api"]')) {
    const tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    document.head.appendChild(tag);
  }
}

const previousYouTubeReady = window.onYouTubeIframeAPIReady;
window.onYouTubeIframeAPIReady = function onYouTubeIframeAPIReady() {
  if (typeof previousYouTubeReady === 'function') {
    previousYouTubeReady();
  }
  youtubeApiQueue.splice(0).forEach((callback) => callback());
};

function bindSeamlessMp4Loop(video) {
  video.removeAttribute('loop');

  video.addEventListener('timeupdate', () => {
    const { duration, currentTime } = video;
    if (!Number.isFinite(duration) || duration <= 0) return;
    if (currentTime >= duration - 0.15) {
      video.currentTime = 0.001;
    }
  });

  video.addEventListener('ended', () => {
    video.currentTime = 0;
    video.play().catch(() => {});
  });
}

function bindSeamlessYouTubeLoop(iframe) {
  if (!iframe.id || !iframe.dataset.youtubeId) return;

  whenYouTubeReady(() => {
    const player = new YT.Player(iframe.id, {
      events: {
        onReady: (event) => {
          event.target.mute();

          window.setInterval(() => {
            const duration = event.target.getDuration();
            const currentTime = event.target.getCurrentTime();
            if (duration > 0 && currentTime >= duration - 0.35) {
              event.target.seekTo(0, true);
            }
          }, 120);
        },
        onStateChange: (event) => {
          if (event.data === YT.PlayerState.ENDED) {
            event.target.seekTo(0, true);
            event.target.playVideo();
          }
        },
      },
    });
  });
}

function initSingleSlideHeroLoop(slide) {
  const video = slide.querySelector('.hero-slider__video');
  if (video) {
    bindSeamlessMp4Loop(video);
    playHeroVideo(video);
    return;
  }

  const iframe = slide.querySelector('.hero-slider__embed iframe');
  if (iframe) {
    bindSeamlessYouTubeLoop(iframe);
  }
}

function initHeroSlider() {
  const hero = document.querySelector('[data-hero-slider]');
  if (!hero) return;

  const slides = [...hero.querySelectorAll('[data-hero-slide]')];
  if (!slides.length) return;

  const dots = [...hero.querySelectorAll('[data-hero-dot]')];
  const eyebrow = document.querySelector('[data-hero-eyebrow]');
  const title = document.querySelector('[data-hero-title]');
  const subtitle = document.querySelector('[data-hero-subtitle]');
  let current = 0;
  let timer;

  function show(index) {
    current = (index + slides.length) % slides.length;
    slides.forEach((slide, i) => {
      const isActive = i === current;
      slide.classList.toggle('is-active', isActive);

      const video = slide.querySelector('.hero-slider__video');
      if (video) {
        if (isActive) {
          playHeroVideo(video);
        } else {
          video.pause();
          video.currentTime = 0;
        }
      }

      syncHeroEmbed(slide, isActive);
    });
    dots.forEach((dot, i) => dot.classList.toggle('is-active', i === current));

    const active = slides[current];
    if (eyebrow) eyebrow.textContent = active.dataset.eyebrow || '';
    if (title) title.innerHTML = (active.dataset.title || '').replace(/\n/g, '<br>');
    if (subtitle) subtitle.textContent = active.dataset.subtitle || '';
  }

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      show(index);
      resetTimer();
    });
  });

  function resetTimer() {
    clearInterval(timer);
    if (slides.length <= 1) return;
    timer = setInterval(() => show(current + 1), 7000);
  }

  if (slides.length === 1) {
    slides[0].classList.add('is-active');
    initSingleSlideHeroLoop(slides[0]);
    return;
  }

  show(0);
  resetTimer();
}
