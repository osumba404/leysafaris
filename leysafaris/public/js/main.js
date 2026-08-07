document.addEventListener('DOMContentLoaded', () => {
  lucide.createIcons();

  initMobileNav();
  initHeaderScroll();
  initAccordions();
  initInquiryForm();
  initSmoothScroll();
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
    if (icon) {
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
      if (icon) {
        icon.setAttribute('data-lucide', 'menu');
        lucide.createIcons();
      }
    });
  });
}

function initHeaderScroll() {
  const header = document.getElementById('header');
  if (!header) return;

  const onScroll = () => {
    header.classList.toggle('header--scrolled', window.scrollY > 20);
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

function initAccordions() {
  document.querySelectorAll('.accordion__trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const panel = document.getElementById(trigger.getAttribute('aria-controls'));
      const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

      document.querySelectorAll('.accordion__trigger').forEach(other => {
        if (other !== trigger) {
          other.setAttribute('aria-expanded', 'false');
          const otherPanel = document.getElementById(other.getAttribute('aria-controls'));
          if (otherPanel) otherPanel.hidden = true;
        }
      });

      trigger.setAttribute('aria-expanded', String(!isExpanded));
      if (panel) panel.hidden = isExpanded;
    });
  });
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
