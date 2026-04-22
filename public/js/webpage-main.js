/**
 * FarsanHub Landing Page – Main JS
 * Handles: AOS, navbar scroll/active, hamburger, back-to-top,
 *          stat counters, contact form → WhatsApp, footer year
 */
(function () {
  'use strict';

  /* ── Helpers ───────────────────────────────────────────────── */
  function qs(sel, ctx)  { return (ctx || document).querySelector(sel); }
  function qsa(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }
  function sanitize(str) {
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }

  /* ── AOS ────────────────────────────────────────────────────── */
  function initAOS() {
    function run() {
      AOS.init({
        duration: 650,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50,
        disable: function () {
          return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        }
      });
    }
    if (typeof AOS !== 'undefined') { run(); }
    else { window.addEventListener('load', function () { if (typeof AOS !== 'undefined') run(); }, { once: true }); }
  }

  /* ── Navbar scroll shadow + active link ─────────────────────── */
  function initNavbar() {
    var nav   = qs('#fhNav');
    var links = qsa('.fh-nav-link');

    var sections = links.map(function (link) {
      var id  = link.getAttribute('href').replace('#', '');
      var sec = qs('#' + id);
      return sec ? { el: sec, link: link } : null;
    }).filter(Boolean);

    function onScroll() {
      var y = window.scrollY;
      if (nav) nav.classList.toggle('scrolled', y > 20);

      var current = '';
      sections.forEach(function (item) {
        var top = item.el.offsetTop - (nav ? nav.offsetHeight : 72) - 10;
        if (y >= top) current = item.el.id;
      });
      links.forEach(function (link) {
        var href = link.getAttribute('href').replace('#', '');
        link.classList.toggle('active', href === current);
      });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ── Hamburger menu ─────────────────────────────────────────── */
  function initHamburger() {
    var btn  = qs('#fhHamburger');
    var menu = qs('#fhMobileMenu');
    if (!btn || !menu) return;

    btn.addEventListener('click', function () {
      var isOpen = menu.classList.toggle('open');
      btn.classList.toggle('open', isOpen);
      btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      menu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    });

    // Close when a mobile link is clicked
    qsa('.fh-mobile-link, .fh-mobile-cta a', menu).forEach(function (link) {
      link.addEventListener('click', function () {
        menu.classList.remove('open');
        btn.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
        menu.setAttribute('aria-hidden', 'true');
      });
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      var nav = qs('#fhNav');
      if (nav && !nav.contains(e.target) && menu.classList.contains('open')) {
        menu.classList.remove('open');
        btn.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
        menu.setAttribute('aria-hidden', 'true');
      }
    });
  }

  /* ── Back-to-top ────────────────────────────────────────────── */
  function initBackToTop() {
    var btn = qs('#fhBackTop');
    if (!btn) return;
    window.addEventListener('scroll', function () {
      btn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ── Footer year ────────────────────────────────────────────── */
  function initYear() {
    var el = qs('#fhYear');
    if (el) el.textContent = new Date().getFullYear();
  }

  /* ── Stat counter animation ─────────────────────────────────── */
  function initCounters() {
    var nums = qsa('.fh-stat-num');
    if (!nums.length || !('IntersectionObserver' in window)) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el     = entry.target;
        var target = parseInt(el.dataset.target, 10);
        if (isNaN(target)) return;
        var start    = 0;
        var duration = 1400;
        var timer = setInterval(function () {
          start += Math.ceil(target / (duration / 16));
          if (start >= target) { start = target; clearInterval(timer); }
          el.textContent = start;
        }, 16);
        observer.unobserve(el);
      });
    }, { threshold: 0.6 });

    nums.forEach(function (el) { observer.observe(el); });
  }

  /* ── Contact form → WhatsApp ────────────────────────────────── */
  function initContactForm() {
    var form      = qs('#fhContactForm');
    var msgBox    = qs('#fhFormMsg');
    var submitBtn = qs('#fhSubmitBtn');
    if (!form) return;

    function showMsg(text, type) {
      if (!msgBox) return;
      msgBox.textContent = text;
      msgBox.className = 'fh-form-msg ' + type;
      msgBox.classList.remove('d-none');
      msgBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      setTimeout(function () { msgBox.classList.add('d-none'); }, 6000);
    }

    function validateField(field) {
      field.classList.remove('invalid');
      var valid = field.checkValidity();
      if (field.name === 'phone' && field.value) valid = /^[0-9]{10}$/.test(field.value);
      if (field.name === 'email' && field.value)  valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
      if (!valid) field.classList.add('invalid');
      return valid;
    }

    qsa('[required]', form).forEach(function (f) {
      f.addEventListener('blur',  function () { validateField(f); });
      f.addEventListener('input', function () { if (f.classList.contains('invalid')) validateField(f); });
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var hp = form.querySelector('[name="website"]');
      if (hp && hp.value) { showMsg('Thank you! We will get back to you.', 'success'); return; }

      var allValid = true;
      qsa('[required]', form).forEach(function (f) { if (!validateField(f)) allValid = false; });
      if (!allValid) {
        showMsg('Please fill in all required fields correctly.', 'error');
        var first = qs('.invalid', form);
        if (first) first.focus();
        return;
      }

      var name    = qs('#fhName');
      var phone   = qs('#fhPhone');
      var subject = qs('#fhSubject');
      var message = qs('#fhMessage');
      if (!name || !phone || !message) { showMsg('Form error. Please refresh and try again.', 'error'); return; }

      var data = {
        name:    sanitize(name.value.trim()),
        phone:   sanitize(phone.value.trim()),
        subject: sanitize(subject ? subject.value : ''),
        message: sanitize(message.value.trim())
      };

      if (submitBtn) {
        qs('.fh-btn-text',    submitBtn).classList.add('d-none');
        qs('.fh-btn-loading', submitBtn).classList.remove('d-none');
        submitBtn.disabled = true;
      }

      var waText = encodeURIComponent(
        'FarsanHub Portal Inquiry\n\n' +
        'Name: '    + data.name    + '\n' +
        'Phone: '   + data.phone   + '\n' +
        (data.subject ? 'Query Type: ' + data.subject + '\n' : '') +
        'Message: ' + data.message
      );

      setTimeout(function () {
        window.open('https://wa.me/919574659456?text=' + waText, '_blank', 'noopener,noreferrer');
        form.reset();
        qsa('.invalid', form).forEach(function (f) { f.classList.remove('invalid'); });
        if (submitBtn) {
          qs('.fh-btn-text',    submitBtn).classList.remove('d-none');
          qs('.fh-btn-loading', submitBtn).classList.add('d-none');
          submitBtn.disabled = false;
        }
        showMsg('Message sent! We will contact you on WhatsApp shortly.', 'success');
      }, 700);
    });
  }

  /* ── Workflow step highlight on scroll ──────────────────────── */
  function initWorkflowSteps() {
    var steps = qsa('.fh-step');
    if (!steps.length || !('IntersectionObserver' in window)) return;
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) entry.target.classList.add('fh-step-active');
      });
    }, { threshold: 0.6 });
    steps.forEach(function (s) { observer.observe(s); });
  }

  /* ── Module card hover elevation ───────────────────────────── */
  function initModuleCards() {
    qsa('.fh-module-card').forEach(function (card) {
      card.addEventListener('mouseenter', function () { card.style.willChange = 'transform'; });
      card.addEventListener('mouseleave', function () { card.style.willChange = '';          });
    });
  }

  /* ── SaaS Modules Sidebar ─────────────────────────────────── */
  function initSaaSModules() {
    var desktopBtns = qsa('.fh-ms-btn');
    var mobileBtns = qsa('.fh-oc-modules-list .list-group-item');
    var panels = qsa('.fh-mod-panel');
    var mobileActiveText = qs('#fhMobileModuleActiveText');

    function switchModule(targetId, btnHtml) {
      desktopBtns.forEach(function(b) { b.classList.toggle('active', b.dataset.target === targetId); });
      mobileBtns.forEach(function(b) { b.classList.toggle('active', b.dataset.target === targetId); });
      panels.forEach(function(p) { p.classList.toggle('active', p.id === targetId); });
      if (mobileActiveText && btnHtml) {
        mobileActiveText.innerHTML = btnHtml;
      }
    }

    desktopBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        switchModule(btn.dataset.target, btn.innerHTML);
      });
    });

    mobileBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        switchModule(btn.dataset.target, btn.innerHTML);
      });
    });
  }

  /* ── Init ───────────────────────────────────────────────────── */
  function init() {
    initAOS();
    initNavbar();
    initHamburger();
    initBackToTop();
    initYear();
    initCounters();
    initContactForm();
    initWorkflowSteps();
    initModuleCards();
    initSaaSModules();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
